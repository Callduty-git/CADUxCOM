<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Producto;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador ReviewController - Maneja las reseñas y calificaciones
 * 
 * Este controlador permite a los usuarios crear, editar y gestionar
 * reseñas de productos con sistema de moderación.
 */
class ReviewController extends Controller
{
    /**
     * Constructor - Aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar reseñas de un producto específico
     * 
     * @param int $productId
     * @return \Illuminate\View\View
     */
    public function index($productId)
    {
        $product = Producto::with(['empresa', 'subcategoria'])->findOrFail($productId);
        
        $reviews = Review::with(['user'])
            ->byProduct($productId)
            ->approved()
            ->latest()
            ->paginate(10);

        $stats = Review::getProductStats($productId);
        
        // Verificar si el usuario puede reseñar este producto
        $canReview = $this->canUserReview($productId);
        $userReview = null;
        
        if (Auth::check()) {
            $userReview = Review::byUser(Auth::id())
                ->byProduct($productId)
                ->first();
        }

        return view('reviews.index', compact('product', 'reviews', 'stats', 'canReview', 'userReview'));
    }

    /**
     * Mostrar formulario para crear una reseña
     * 
     * @param int $productId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($productId)
    {
        $product = Producto::findOrFail($productId);
        
        // Verificar si el usuario puede reseñar
        if (!$this->canUserReview($productId)) {
            return redirect()->route('productos.show', $productId)
                ->with('error', 'No puedes reseñar este producto. Debes haberlo comprado primero.');
        }

        // Verificar si ya tiene una reseña
        $existingReview = Review::byUser(Auth::id())
            ->byProduct($productId)
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id);
        }

        return view('reviews.create', compact('product'));
    }

    /**
     * Almacenar una nueva reseña
     * 
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:2000',
        ]);

        $product = Producto::findOrFail($productId);

        // Verificar si el usuario puede reseñar
        if (!$this->canUserReview($productId)) {
            return back()->with('error', 'No puedes reseñar este producto.');
        }

        // Verificar si ya tiene una reseña
        $existingReview = Review::byUser(Auth::id())
            ->byProduct($productId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Ya tienes una reseña para este producto.');
        }

        // Crear la reseña
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'is_verified_purchase' => $this->hasUserPurchasedProduct($productId),
            'status' => Review::STATUS_PENDING,
        ]);

        return redirect()->route('reviews.index', $productId)
            ->with('success', 'Tu reseña ha sido enviada y está pendiente de moderación.');
    }

    /**
     * Mostrar formulario para editar una reseña
     * 
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $review = Review::with(['product', 'user'])->findOrFail($id);

        // Verificar que el usuario es el propietario de la reseña
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index', $review->product_id)
                ->with('error', 'No tienes permisos para editar esta reseña.');
        }

        // Verificar que la reseña puede ser editada
        if (!$review->canBeEdited()) {
            return redirect()->route('reviews.index', $review->product_id)
                ->with('error', 'Esta reseña no puede ser editada.');
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Actualizar una reseña existente
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:2000',
        ]);

        $review = Review::findOrFail($id);

        // Verificar que el usuario es el propietario de la reseña
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para editar esta reseña.');
        }

        // Verificar que la reseña puede ser editada
        if (!$review->canBeEdited()) {
            return back()->with('error', 'Esta reseña no puede ser editada.');
        }

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'status' => Review::STATUS_PENDING, // Volver a pendiente para moderación
        ]);

        return redirect()->route('reviews.index', $review->product_id)
            ->with('success', 'Tu reseña ha sido actualizada y está pendiente de moderación.');
    }

    /**
     * Eliminar una reseña
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Verificar que el usuario es el propietario de la reseña
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para eliminar esta reseña.');
        }

        // Verificar que la reseña puede ser eliminada
        if (!$review->canBeDeleted()) {
            return back()->with('error', 'Esta reseña no puede ser eliminada.');
        }

        $productId = $review->product_id;
        $review->delete();

        return redirect()->route('reviews.index', $productId)
            ->with('success', 'Tu reseña ha sido eliminada.');
    }

    /**
     * Marcar una reseña como útil
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markHelpful(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        // Aquí podrías implementar lógica para evitar votos duplicados
        // Por simplicidad, permitimos múltiples votos
        
        $review->markAsHelpful();

        return response()->json([
            'success' => true,
            'helpful_count' => $review->fresh()->helpful_count,
            'helpfulness_percentage' => $review->fresh()->getHelpfulnessPercentage(),
        ]);
    }

    /**
     * Marcar una reseña como no útil
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotHelpful(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        $review->markAsNotHelpful();

        return response()->json([
            'success' => true,
            'not_helpful_count' => $review->fresh()->not_helpful_count,
            'helpfulness_percentage' => $review->fresh()->getHelpfulnessPercentage(),
        ]);
    }

    /**
     * Obtener estadísticas de reseñas para un producto (API)
     * 
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats($productId)
    {
        $stats = Review::getProductStats($productId);
        
        return response()->json($stats);
    }

    /**
     * Verificar si un usuario puede reseñar un producto
     * 
     * @param int $productId
     * @return bool
     */
    private function canUserReview($productId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        // Verificar si ya tiene una reseña
        $existingReview = Review::byUser(Auth::id())
            ->byProduct($productId)
            ->first();

        if ($existingReview) {
            return false;
        }

        // Verificar si ha comprado el producto
        return $this->hasUserPurchasedProduct($productId);
    }

    /**
     * Verificar si un usuario ha comprado un producto específico
     * 
     * @param int $productId
     * @return bool
     */
    private function hasUserPurchasedProduct($productId): bool
    {
        return Order::where('user_id', Auth::id())
            ->where('status', '!=', Order::STATUS_CANCELLED)
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    /**
     * Mostrar reseñas del usuario autenticado
     * 
     * @return \Illuminate\View\View
     */
    public function myReviews()
    {
        $reviews = Review::with(['product.empresa'])
            ->byUser(Auth::id())
            ->latest()
            ->paginate(10);

        return view('reviews.my-reviews', compact('reviews'));
    }
}