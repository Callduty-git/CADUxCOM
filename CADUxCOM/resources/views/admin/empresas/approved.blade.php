<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas Aprobadas - CADUxCOM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <x-admin.back-button href="{{ route('admin.dashboard') }}" />
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Empresas Aprobadas</h1>
                <a href="{{ route('admin.empresas.pending') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Pendientes</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($empresasAprobadas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIT</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aprobada</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($empresasAprobadas as $empresa)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="ml-0">
                                            <div class="text-sm font-medium text-gray-900">{{ $empresa->Nombre }}</div>
                                            <div class="text-sm text-gray-500">{{ $empresa->Municipio }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $empresa->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $empresa->NIT }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($empresa->approved_at)->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.empresas.show', ['empresa' => $empresa, 'return_to' => 'approved']) }}" class="text-blue-600 hover:text-blue-900">Ver Detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $empresasAprobadas->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">No hay empresas aprobadas.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>