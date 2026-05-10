
<div class="destination-section">
    <div class="section-header">
        <h2>Những điểm đến quốc tế hàng đầu</h2>
        <div class="underline"></div>
    </div>

    <div class="destination-grid">
        @php
            $fallbackImages = [
                'https://images.unsplash.com/photo-1508009603885-50cf7c579367?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1514395462725-fb4566210144?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1541535650810-10d26f5c2abb?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&q=80&w=800',
            ];
        @endphp

        @foreach($featuredDestinations->take(9) as $index => $destination)
            @php
                $imageUrl = $destination->image ? (Str::startsWith($destination->image, 'http') ? $destination->image : asset($destination->image)) : $fallbackImages[$index % count($fallbackImages)];
            @endphp
            <a href="{{ route('destinations.show', $destination->id) }}" class="dest-card dest-{{ $index + 1 }}">
                <div class="dest-overlay"></div>
                <img src="{{ $imageUrl }}" alt="{{ $destination->city }}">
                <div class="dest-content">
                    <h3>{{ $destination->city }}</h3>
                    <div class="dest-line"></div>
                </div>
            </a>
        @endforeach
    </div>
</div>
