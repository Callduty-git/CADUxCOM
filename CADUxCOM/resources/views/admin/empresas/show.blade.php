<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Empresa - CADUxCOM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @php
        $returnTo = request('return_to');
        $backHref = match($returnTo) {
            'pending' => route('admin.empresas.pending'),
            'approved' => route('admin.empresas.approved'),
            'rejected' => route('admin.empresas.rejected'),
            default => route('admin.dashboard'),
        };
    @endphp
    <x-admin.back-button href="{{ $backHref }}" />
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Verificar Empresa: {{ $empresa->Nombre }}</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información de la Empresa -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Información General</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                                <p class="text-sm text-gray-900">{{ $empresa->Nombre }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="text-sm text-gray-900">{{ $empresa->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIT</label>
                                <p class="text-sm text-gray-900">{{ $empresa->NIT }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contacto</label>
                                <p class="text-sm text-gray-900">{{ $empresa->Contacto }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <p class="text-sm text-gray-900">{{ $empresa->Direccion }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipio</label>
                                <p class="text-sm text-gray-900">{{ $empresa->Municipio }}</p>
                            </div>
                            @if($empresa->Ubicacion)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                    <p class="text-sm text-gray-900">{{ $empresa->Ubicacion }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Registro</label>
                                <p class="text-sm text-gray-900">{{ $empresa->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Documentos</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto de la Empresa</label>
                                <div class="border border-gray-300 rounded-lg p-4 text-center">
                                    <img src="{{ Storage::url($empresa->Foto) }}" 
                                         alt="Foto de {{ $empresa->Nombre }}" 
                                         class="max-w-full h-48 object-cover mx-auto rounded">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Certificado de Cámara de Comercio</label>
                                <div class="border border-gray-300 rounded-lg p-4 text-center">
                                    <a href="{{ route('admin.empresas.certificado', $empresa) }}" 
                                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block">
                                        📄 Descargar Certificado
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="mt-8 border-t pt-6">
                <div class="flex justify-center space-x-4">
                    <form action="{{ route('admin.empresas.approve', $empresa) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-medium"
                                onclick="return confirm('¿Estás seguro de que quieres aprobar esta empresa?')">
                            ✅ Aprobar Empresa
                        </button>
                    </form>

                    <button type="button" 
                            class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 font-medium"
                            onclick="showRejectModal()">
                        ❌ Rechazar Empresa
                    </button>

                    @if($empresa->status === 'approved')
                        <form action="{{ route('admin.empresas.destroy', ['empresa' => $empresa, 'return_to' => request('return_to')]) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar esta empresa y sus datos asociados? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-gray-900 font-medium">
                                🗑️ Eliminar Empresa
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Rechazo -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-4">Rechazar Empresa</h3>
                <form action="{{ route('admin.empresas.reject', $empresa) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo del Rechazo
                        </label>
                        <textarea id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="4" 
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                  placeholder="Explica el motivo del rechazo..."
                                  required></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" 
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                                onclick="hideRejectModal()">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Rechazar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</body>
</html>







