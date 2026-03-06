<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Редагувати: {{ $event->title }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

        <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data"
              x-data="{ imageError: '', isOnline: {{ old('is_online', $event->is_online) ? 'true' : 'false' }} }"
              class="bg-gray-900 border border-gray-800 rounded-2xl p-8 space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-input-label for="title" value="Назва заходу *" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                  value="{{ old('title', $event->title) }}" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="category_id" value="Категорія *" />
                    <select id="category_id" name="category_id"
                            class="mt-1 block w-full bg-gray-800 border-gray-700 text-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $event->category_id) == $cat->id)>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="image" value="Нове фото (необов'язково)" />
                    @if($event->image)
                        <img src="{{ Storage::disk('public')->url($event->image) }}" class="mt-1 h-16 rounded-lg object-cover mb-2">
                    @endif
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/gif,image/webp"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:text-indigo-400 hover:file:bg-indigo-500/30"
                           x-on:change="const f=$event.target.files[0]; if(f&&f.size>2*1024*1024){imageError='Розмір фото перевищує 2 МБ';$event.target.value=''}else{imageError=''}">
                    <p x-show="imageError" x-text="imageError" class="mt-1 text-sm text-red-400"></p>
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF, WebP · до 2 МБ</p>
                </div>

                <div>
                    <x-input-label for="starts_at" value="Початок *" />
                    <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full"
                                  value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}" required />
                    <x-input-error :messages="$errors->get('starts_at')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="ends_at" value="Кінець *" />
                    <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full"
                                  value="{{ old('ends_at', $event->ends_at->format('Y-m-d\TH:i')) }}" required />
                    <x-input-error :messages="$errors->get('ends_at')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="price" value="Ціна (грн) *" />
                    <x-text-input id="price" name="price" type="number" min="0" step="1" class="mt-1 block w-full"
                                  value="{{ old('price', $event->price) }}" required />
                    <x-input-error :messages="$errors->get('price')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="capacity" value="Кількість місць *" />
                    <x-text-input id="capacity" name="capacity" type="number" min="1" class="mt-1 block w-full"
                                  value="{{ old('capacity', $event->capacity) }}" required />
                    <x-input-error :messages="$errors->get('capacity')" class="mt-1" />
                </div>

                <!-- Online toggle -->
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_online" value="1" x-model="isOnline"
                               @if(old('is_online', $event->is_online)) checked @endif
                               class="rounded border-gray-600 bg-gray-800 text-indigo-500 shadow-sm focus:ring-indigo-500 focus:ring-offset-gray-900">
                        <span class="text-sm font-medium text-gray-300">Онлайн захід</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Відмітьте, якщо захід проводиться в інтернеті (Zoom, YouTube тощо)</p>
                </div>

                <!-- Online URL -->
                <div class="md:col-span-2" x-show="isOnline" x-cloak>
                    <x-input-label for="online_url" value="Посилання на захід (необов'язково)" />
                    <x-text-input id="online_url" name="online_url" type="url" class="mt-1 block w-full"
                                  value="{{ old('online_url', $event->online_url) }}" placeholder="https://zoom.us/j/..." />
                    <x-input-error :messages="$errors->get('online_url')" class="mt-1" />
                </div>

                <!-- Location -->
                <div x-show="!isOnline" x-cloak>
                    <x-input-label for="location" value="Назва місця *" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                                  value="{{ old('location', $event->location) }}"
                                  x-bind:required="!isOnline" />
                    <x-input-error :messages="$errors->get('location')" class="mt-1" />
                </div>

                <div x-show="!isOnline" x-cloak>
                    <x-input-label for="address" value="Адреса" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                                  value="{{ old('address', $event->address) }}" />
                </div>

                <!-- Map picker -->
                <div class="md:col-span-2" x-show="!isOnline" x-cloak>
                    <x-input-label value="Місце на карті (необов'язково)" />
                    <p class="text-xs text-gray-500 mb-2 mt-0.5">Натисніть на карту, щоб змінити позначку місця проведення</p>
                    <div id="picker-map" class="w-full rounded-xl border border-gray-700" style="height:280px;"></div>
                    @php $initLat = old('latitude', $event->latitude); $initLng = old('longitude', $event->longitude); @endphp
                    <p id="coords-hint" class="text-xs text-gray-500 mt-1">
                        @if($initLat) {{ $initLat }}, {{ $initLng }} @else Позначку не встановлено @endif
                    </p>
                    <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude', $event->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $event->longitude) }}">
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="description" value="Опис *" />
                    <textarea id="description" name="description" rows="5"
                              class="mt-1 block w-full bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              required>{{ old('description', $event->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
            </div>

            <div class="flex gap-4 pt-2">
                <button type="submit"
                        class="bg-indigo-500 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-400 transition font-medium">
                    Зберегти зміни
                </button>
                <a href="{{ route('organizer.events.show', $event) }}"
                   class="px-6 py-2.5 rounded-lg border border-gray-700 text-gray-400 hover:bg-gray-800 transition">
                    Скасувати
                </a>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('alpine:initialized', function () {
            const isOnline = {{ old('is_online', $event->is_online) ? 'true' : 'false' }};
            if (isOnline) return;

            const initLat = @json(old('latitude', $event->latitude));
            const initLng = @json(old('longitude', $event->longitude));
            const defaultCenter = [50.4501, 30.5234];

            const map = L.map('picker-map').setView(
                initLat ? [parseFloat(initLat), parseFloat(initLng)] : defaultCenter, 12
            );
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            setTimeout(() => map.invalidateSize(), 50);

            let marker = initLat
                ? L.marker([parseFloat(initLat), parseFloat(initLng)]).addTo(map)
                : null;

            map.on('click', function (e) {
                const { lat, lng } = e.latlng;
                if (marker) marker.setLatLng(e.latlng);
                else marker = L.marker(e.latlng).addTo(map);
                document.getElementById('latitude').value  = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
                document.getElementById('coords-hint').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
            });
        });
    </script>
</x-app-layout>
