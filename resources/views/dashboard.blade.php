<x-dashboard heading="Resumen de Ventas">

    @if($sales->isEmpty())
        <p class="text-gray-300">No hay ventas registradas aún.</p>
    @else
        @foreach($sales as $sale)
            <div class="mb-6 p-4 bg-gray-700 rounded-lg">
                <h2 class="text-xl font-bold">Venta #{{ $sale->id }}</h2>
                <p><strong>Cliente:</strong> {{ $sale->user->name }} ({{ $sale->user->email }})</p>
                <p><strong>Fecha:</strong> {{ $sale->sale_date }}</p>
                <p><strong>Total:</strong> ${{ number_format($sale->total_price, 2) }}</p>

                <h3 class="mt-4 font-semibold">Productos vendidos:</h3>
                <ul class="list-disc ml-6">
                    @foreach($sale->products as $product)
                        <li>
                            {{ $product->name }} - Cantidad: {{ $product->pivot->quantity }} - Precio: ${{ number_format($product->price, 2) }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endif


   
</x-dashboard>
