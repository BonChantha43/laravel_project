<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Sale Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body { font-family: 'Siemreap', sans-serif; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        .cursor-grab { cursor: grab; cursor: -webkit-grab; }
        .cursor-grabbing { cursor: grabbing; cursor: -webkit-grabbing; }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex text-gray-800">

    <div class="w-2/3 flex flex-col h-full border-r">
        <div class="bg-white p-3 shadow-sm flex justify-between items-center z-10 border-b gap-4">
            <h1 class="text-xl font-bold text-blue-600 flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-store"></i> ប្រព័ន្ធលក់ POS
            </h1>
            <div class="relative w-full">
                <input type="text" id="search" placeholder="ស្វែងរកឈ្មោះទំនិញ ឬ Barcode..."
                    class="w-full pl-10 pr-4 py-2 border rounded-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm">
                <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
            </div>
        </div>

        <div id="category-list" class="bg-white px-3 py-3 border-b flex gap-3 overflow-x-auto whitespace-nowrap hide-scroll shadow-sm cursor-grab select-none">
            <button onclick="filterCategory('all')" id="cat-btn-all"
                class="category-btn active flex-shrink-0 px-6 py-2 rounded-full text-sm font-bold transition border border-blue-600 bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                <i class="fas fa-th-large mr-1"></i> ទាំងអស់
            </button>
            @foreach($categories as $cat)
            <button onclick="filterCategory('{{ $cat->id }}')" id="cat-btn-{{ $cat->id }}"
                class="category-btn flex-shrink-0 px-6 py-2 rounded-full text-sm font-bold transition border border-gray-200 bg-gray-50 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <div class="flex-1 overflow-y-auto p-4 bg-gray-100">
            <div class="grid grid-cols-3 xl:grid-cols-4 gap-4" id="product-grid">
                @foreach($products as $product)
                <div class="product-item bg-white rounded-xl shadow-sm hover:shadow-lg cursor-pointer transition transform hover:-translate-y-1 overflow-hidden border border-gray-100 relative group"
                    data-name="{{ strtolower($product->name) }}"
                    data-barcode="{{ strtolower($product->barcode) }}"
                    data-category-id="{{ $product->category_id }}"
                    onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->sale_price }}, {{ $product->qty }})">

                    <div class="h-36 bg-gray-50 flex items-center justify-center border-b overflow-hidden relative">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <i class="fas fa-box-open text-4xl text-gray-300"></i>
                        @endif
                        <div class="absolute top-2 right-2">
                            @if($product->qty > 0)
                                <span class="text-xs font-bold text-white bg-green-500 px-2 py-1 rounded-full shadow-sm">{{ $product->qty }}</span>
                            @else
                                <span class="text-xs font-bold text-white bg-red-500 px-2 py-1 rounded-full shadow-sm">Os</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3">
                        <h3 class="font-bold text-gray-700 truncate text-md mb-1">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-400 mb-2">Code: {{ $product->barcode }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-bold text-lg">${{ number_format($product->sale_price, 2) }}</span>
                            <button class="bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white p-1.5 rounded-lg transition pointer-events-none">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div id="no-result" class="hidden text-center mt-20 text-gray-400">
                <i class="fas fa-search text-4xl mb-2 opacity-50"></i>
                <p>រកមិនឃើញទំនិញដែលស្វែងរក</p>
            </div>
        </div>
    </div>

    <div class="w-1/3 bg-white shadow-2xl flex flex-col h-full z-20">
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center shadow-sm">
            <h2 class="text-lg font-bold text-gray-700"><i class="fas fa-shopping-cart text-blue-500"></i> បញ្ជីទិញ</h2>
            <button onclick="clearCart()" class="text-red-500 text-sm hover:bg-red-50 px-3 py-1 rounded transition border border-transparent hover:border-red-200">
                <i class="fas fa-trash-alt"></i> សម្អាត
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-2 bg-white" id="cart-items">
            <div id="empty-cart-msg" class="text-center text-gray-300 mt-20 flex flex-col items-center">
                <i class="fas fa-basket-shopping text-6xl mb-4 opacity-30"></i>
                <p>មិនទាន់មានទំនិញក្នុងកន្ត្រក</p>
            </div>
        </div>

        <div class="p-5 bg-gray-50 border-t space-y-3 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between text-gray-600 text-sm">
                <span>សរុប (Subtotal)</span>
                <span id="subtotal-display" class="font-medium">$0.00</span>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <span>បញ្ចុះតម្លៃ (Discount)</span>
                <div class="relative">
                    <span class="absolute left-2 top-1.5 text-gray-400 text-xs">$</span>
                    <input type="number" id="discount-input" value="0" min="0" oninput="calculateTotal()"
                        class="w-24 text-right border rounded-lg pl-4 pr-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <hr class="border-dashed border-gray-300 my-2">
            <div class="flex justify-between text-xl font-bold text-blue-800">
                <span>សរុបត្រូវបង់</span>
                <span id="final-total-display">$0.00</span>
            </div>
            <button onclick="processSale()" id="pay-btn"
                class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2">
                <i class="fas fa-money-bill-wave"></i> គិតប្រាក់ (Checkout)
            </button>
        </div>
    </div>

    <div id="payment-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full transform scale-100 transition-transform duration-300 overflow-hidden">
            <div class="bg-green-500 p-6 text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                    <i class="fas fa-check text-3xl text-green-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">ការលក់ជោគជ័យ!</h2>
                <p class="text-green-100 mt-1 text-sm">Payment Successful</p>
            </div>

            <div class="p-6 text-center space-y-4">
                
                <div class="flex justify-between border-b pb-2 text-sm">
                    <span class="text-gray-500">វិក្កយបត្រ:</span>
                    <span id="modal-invoice" class="font-bold text-gray-800 font-mono">INV-0000</span>
                </div>
                <div class="flex justify-between border-b pb-2 text-sm">
                    <span class="text-gray-500">សរុប:</span>
                    <span id="modal-total" class="font-bold text-blue-600 text-xl">$0.00</span>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 flex flex-col items-center justify-center">
                    <p class="text-xs text-gray-500 mb-2 font-bold">ស្កេនដើម្បីបង់ប្រាក់ (Scan to Pay)</p>
                    <img id="qr-image" src="" alt="Payment QR" class="w-32 h-32 object-contain bg-white p-2 rounded shadow-sm">
                    <p class="text-[10px] text-gray-400 mt-2">KHQR / Payment Link</p>
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex gap-3">
                <button onclick="printInvoice()" class="flex-1 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold py-3 rounded-xl transition">
                    <i class="fas fa-print"></i> Print
                </button>
                <button onclick="closeModal()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg">
                    បិទ / លក់ថ្មី
                </button>
            </div>
        </div>
    </div>

    <script>
        // DRAG SCROLL
        const slider = document.getElementById('category-list');
        let isDown = false, startX, scrollLeft;
        slider.addEventListener('mousedown', e => { isDown = true; slider.classList.add('cursor-grabbing'); startX = e.pageX - slider.offsetLeft; scrollLeft = slider.scrollLeft; });
        slider.addEventListener('mouseleave', () => { isDown = false; slider.classList.remove('cursor-grabbing'); });
        slider.addEventListener('mouseup', () => { isDown = false; slider.classList.remove('cursor-grabbing'); });
        slider.addEventListener('mousemove', e => { if(!isDown) return; e.preventDefault(); const x = e.pageX - slider.offsetLeft; const walk = (x - startX) * 2; slider.scrollLeft = scrollLeft - walk; });

        // POS LOGIC
        let cart = [];

        function filterCategory(catId) {
            let items = document.querySelectorAll('.product-item');
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
                btn.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-200');
            });
            let activeBtn = document.getElementById('cat-btn-' + catId);
            if (activeBtn) {
                activeBtn.classList.remove('bg-gray-50', 'text-gray-600', 'border-gray-200');
                activeBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
            }
            let hasResult = false;
            items.forEach(item => {
                let itemCatId = item.getAttribute('data-category-id');
                if (catId === 'all' || itemCatId == catId) {
                    item.style.display = 'block'; hasResult = true;
                } else {
                    item.style.display = 'none';
                }
            });
            document.getElementById('no-result').style.display = hasResult ? 'none' : 'block';
        }

        document.getElementById('search').addEventListener('keyup', function(e) {
            let term = e.target.value.toLowerCase().trim();
            let items = document.querySelectorAll('.product-item');
            let hasResult = false;
            items.forEach(item => {
                let name = item.getAttribute('data-name');
                let barcode = item.getAttribute('data-barcode');
                if (name.includes(term) || barcode.includes(term)) {
                    item.style.display = 'block'; hasResult = true;
                } else {
                    item.style.display = 'none';
                }
            });
            document.getElementById('no-result').style.display = hasResult ? 'none' : 'block';
        });

        function addToCart(id, name, price, maxQty) {
            if (maxQty <= 0) return alert('ទំនិញនេះអស់ស្តុកហើយ!');
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                if (existingItem.qty + 1 > maxQty) return alert('ស្តុកនៅសល់តែ ' + maxQty + ' ប៉ុណ្ណោះ!');
                existingItem.qty++;
            } else {
                cart.push({ id, name, price, qty: 1, maxQty: maxQty });
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('empty-cart-msg');
            container.innerHTML = '';
            if (cart.length === 0) { if(emptyMsg) emptyMsg.style.display = 'flex'; }
            else {
                if(emptyMsg) emptyMsg.style.display = 'none';
                cart.forEach((item, index) => {
                    const html = `
                        <div class="flex flex-col bg-white p-3 rounded-lg border border-gray-100 shadow-sm mb-2">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-700 text-sm w-3/4">${item.name}</h4>
                                <button onclick="removeItem(${index})" class="text-gray-300 hover:text-red-500"><i class="fas fa-times-circle"></i></button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500">$${item.price.toFixed(2)}</div>
                                <div class="flex items-center border border-gray-200 rounded-md">
                                    <button onclick="updateQty(${index}, -1)" class="px-2 hover:bg-gray-100">-</button>
                                    <span class="px-2 text-sm font-bold min-w-[20px] text-center">${item.qty}</span>
                                    <button onclick="updateQty(${index}, 1)" class="px-2 hover:bg-gray-100">+</button>
                                </div>
                                <span class="font-bold text-blue-600 text-sm">$${(item.price * item.qty).toFixed(2)}</span>
                            </div>
                        </div>`;
                    container.innerHTML += html;
                });
            }
            calculateTotal();
        }

        function updateQty(index, change) {
            const item = cart[index];
            const newQty = item.qty + change;
            if (newQty > item.maxQty) return alert('មិនអាចលក់លើសចំនួនស្តុកបានទេ!');
            if (newQty > 0) item.qty = newQty;
            else if (confirm('លុបទំនិញនេះចេញ?')) cart.splice(index, 1);
            renderCart();
        }

        function removeItem(index) { cart.splice(index, 1); renderCart(); }
        function clearCart() { if (confirm('សម្អាតកន្ត្រក?')) { cart = []; renderCart(); } }

        function calculateTotal() {
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let discount = parseFloat(document.getElementById('discount-input').value) || 0;
            if (discount > subtotal) { discount = subtotal; document.getElementById('discount-input').value = subtotal; }
            let final = subtotal - discount;
            document.getElementById('subtotal-display').innerText = '$' + subtotal.toFixed(2);
            document.getElementById('final-total-display').innerText = '$' + final.toFixed(2);
        }

        function processSale() {
            if (cart.length === 0) return alert('សូមជ្រើសរើសទំនិញ!');
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const discount = parseFloat(document.getElementById('discount-input').value) || 0;
            
            const btn = document.getElementById('pay-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> កំពុងដំណើរការ...';
            btn.disabled = true;

            axios.post('/api/pos/sale', {
                items: cart.map(i => ({ id: i.id, qty: i.qty })),
                total_amount: subtotal,
                discount: discount,
                final_total: subtotal - discount,
                payment_type: 'cash'
            })
            .then(res => {
                showSuccessModal(res.data.data);
            })
            .catch(err => {
                console.error(err);
                alert('❌ មានបញ្ហា: ' + (err.response?.data?.message || err.message));
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // ✅ Function បង្ហាញ Modal + QR Code
        function showSuccessModal(data) {
            document.getElementById('modal-invoice').innerText = data.invoice_number;
            document.getElementById('modal-total').innerText = '$' + parseFloat(data.final_total).toFixed(2);

            // Generate QR Code (ប្រើ API ឥតគិតថ្លៃសម្រាប់ Demo)
            // យកលេខវិក្កយបត្រ + តម្លៃសរុប មកបង្កើតជា QR
            const qrData = "INV:" + data.invoice_number + "|AMT:" + data.final_total;
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrData)}`;
            
            document.getElementById('qr-image').src = qrUrl;

            const modal = document.getElementById('payment-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            document.getElementById('payment-modal').classList.add('hidden');
            document.getElementById('payment-modal').classList.remove('flex');
            cart = [];
            renderCart();
            document.getElementById('discount-input').value = 0;
            window.location.reload();
        }

        function printInvoice() {
            window.print(); // សាកល្បង Print ធម្មតា
        }
    </script>+
</body>
</html>