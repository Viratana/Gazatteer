<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Locations Map</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: Arial, sans-serif; margin: 16px; }
        #map { height: 70vh; width: 100%; border-radius: 14px; overflow: hidden; }
        .wrap { display: grid; grid-template-columns: 2fr 1fr; gap: 14px; }
        .panel {
            border: 1px solid #333;
            border-radius: 14px;
            padding: 12px;
            min-height: 140px;
        }
        .muted { opacity: .75; }
        .btn {
            display: inline-block;
            padding: 8px 10px;
            border: 1px solid #333;
            border-radius: 10px;
            text-decoration: none;
            color: #111;
            margin-right: 8px;
            margin-top: 10px;
        }
        .row { display:flex; gap:10px; margin-bottom:10px; }
        input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #333;
        }
        @media (max-width: 900px) {
            .wrap { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<h2>Leaflet Map (Locations)</h2>

<div class="wrap">
    <div>
        <div class="row">
            <input id="focusInput" placeholder="Enter Location ID (from Filament) then press Enter" />
        </div>
        <div id="map"></div>
    </div>

    <div class="panel">
        <div><b>Selected location</b></div>
        <div id="selected" class="muted" style="margin-top:8px;">
            Open from Filament “Leaflet Map” button, or click a marker.
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Cambodia default center
    const map = L.map('map').setView([11.5564, 104.9282], 7);

    // OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    const selectedEl = document.getElementById('selected');

    function getFilterKey(typeId) {
        if (typeId == 1) return 'province_id';
        if (typeId == 2) return 'district_id';
        if (typeId == 3) return 'commune_id';
        if (typeId == 4) return 'village_id';
        return null;
    }

    function renderSelectedPanel(loc, hierarchy = []) {
        const kh = loc.name_kh ?? '';
        const en = loc.name_en ?? '';
        const code = loc.code ?? '';
        const typeId = loc.location_type_id ?? loc.type ?? '';
        const lat = loc.lat ?? null;
        const lng = loc.lng ?? null;

        const path = hierarchy.length
            ? hierarchy.map(h => `${h.code ?? ''} - ${(h.name_kh ?? h.name_en ?? '')}`).join(' → ')
            : '';

        const googleQuery = encodeURIComponent((en || kh || code) + ' Cambodia');
        const googleSearchUrl = `https://www.google.com/maps/search/?api=1&query=${googleQuery}`;

        const key = getFilterKey(typeId);
        let filamentUrl = null;
        if (key && loc.id) {
            const u = new URL(window.location.origin + '/admin/locations');
            u.searchParams.set(`tableFilters[location_hierarchy][${key}]`, loc.id);
            filamentUrl = u.toString();
        }

        selectedEl.innerHTML = `
            <div><b>${code} - ${kh}</b></div>
            <div class="muted">${en}</div>
            ${path ? `<div class="muted" style="margin-top:6px;">Hierarchy: ${path}</div>` : ''}
            <div class="muted" style="margin-top:6px;">ID: ${loc.id ?? ''}</div>
            <div class="muted">Type ID: ${typeId}</div>
            ${lat && lng ? `<div class="muted">Lat/Lng: ${lat}, ${lng}</div>` : `<div class="muted" style="color:#f59e0b;">No lat/lng for this record</div>`}

            <div style="margin-top:10px;">
                <a class="btn" target="_blank" href="${googleSearchUrl}">Search on Google Maps</a>
                ${filamentUrl ? `<a class="btn" href="${filamentUrl}">Open Filament Filter</a>` : ''}
            </div>
        `;
    }

    function focusLocationById(id) {
        // Uses API route: /map/location/{id}
        fetch(`/map/location/${id}`)
            .then(r => {
                if (!r.ok) throw new Error('Not found');
                return r.json();
            })
            .then(data => {
                renderSelectedPanel(data.location, data.hierarchy || []);

                // If location has lat/lng -> show marker + zoom
                if (data.location?.lat && data.location?.lng) {
                    const lat = parseFloat(data.location.lat);
                    const lng = parseFloat(data.location.lng);

                    map.setView([lat, lng], 14);
                    L.marker([lat, lng]).addTo(map)
                        .bindPopup(`<b>${data.location.code ?? ''}</b><br>${data.location.name_kh ?? ''}`)
                        .openPopup();
                    return;
                }

                // Otherwise, try to zoom to nearest parent with lat/lng
                const h = (data.hierarchy || []).slice().reverse();
                for (const node of h) {
                    if (node.lat && node.lng) {
                        map.setView([parseFloat(node.lat), parseFloat(node.lng)], 10);
                        L.marker([parseFloat(node.lat), parseFloat(node.lng)]).addTo(map)
                            .bindPopup(`<b>${node.code ?? ''}</b><br>${node.name_kh ?? ''}`)
                            .openPopup();
                        return;
                    }
                }

                // fallback Cambodia view
                map.setView([11.5564, 104.9282], 7);
            })
            .catch(() => {
                selectedEl.innerHTML = `<span style="color:#ef4444">Cannot load location id ${id}. Check /map/location/${id}</span>`;
            });
    }

    // ✅ Read focus= from URL (Filament button opens /map?focus=ID)
    const params = new URLSearchParams(window.location.search);
    const focusId = params.get('focus');
    if (focusId) {
        focusLocationById(focusId);
    }

    // Input box helper
    const focusInput = document.getElementById('focusInput');
    focusInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const v = (focusInput.value || '').trim();
            if (v) focusLocationById(v);
        }
    });

    // ✅ Load markers (only those that have lat/lng) so no crash
    fetch('/map/locations')
        .then(res => res.json())
        .then(rows => {
            const markers = [];

            rows.forEach(loc => {
                if (!loc.lat || !loc.lng) return;

                const lat = parseFloat(loc.lat);
                const lng = parseFloat(loc.lng);

                if (Number.isNaN(lat) || Number.isNaN(lng)) return;

                const marker = L.marker([lat, lng]).addTo(map);

                marker.on('click', () => {
                    marker.bindPopup(`<b>${loc.name_kh ?? ''}</b><br>${loc.name_en ?? ''}`).openPopup();
                    renderSelectedPanel(loc, []);
                });

                markers.push(marker);
            });

            if (markers.length && !focusId) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.2));
            }

            if (!markers.length && !focusId) {
                selectedEl.innerHTML = 'No markers (no lat/lng). Open from Filament “Leaflet Map” button to view details.';
            }
        })
        .catch(err => {
            selectedEl.innerHTML = 'Error loading locations: ' + err;
        });
</script>

</body>
</html>