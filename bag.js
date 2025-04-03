document.addEventListener('DOMContentLoaded', function() {
    const quantityControls = document.querySelectorAll('.quantity-controls');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const sizeSelects = document.querySelectorAll('.size-select');
    const clearBagBtn = document.getElementById('clearBagBtn');

    // Clear bag functionality
    if (clearBagBtn) {
        clearBagBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your bag?')) {
                fetch('clear_bag.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove all items from the bag display
                            const bagItemBox = document.querySelector('.bag-item-box');
                            bagItemBox.innerHTML = `
                                <div class="empty-cart-message">
                                    <i class="fa-solid fa-shopping-bag" style="font-size: 50px; color: #088178; margin-bottom: 20px;"></i>
                                    <h3>Your Shopping Bag is Empty!</h3>
                                    <p>Looks like you haven't added anything to your bag yet.</p>
                                    <a href="shop.php" class="shop-now-btn">Shop Now</a>
                                </div>
                            `;
                            // Update cart count
                            updateCartCount();
                            // Update prices
                            updatePrices();
                        } else {
                            alert('Error clearing bag: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }

    function updateQuantity(cartId, newQuantity, quantityControl) {
        fetch('update_quantity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_id=${cartId}&quantity=${newQuantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const quantityValue = quantityControl.querySelector('.quantity-value');
                quantityValue.textContent = newQuantity;
                updateCartCount();
                updatePrices();
            } else {
                alert('Error updating quantity');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateSize(cartId, newSize) {
        fetch('update_size.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_id=${cartId}&size=${newSize}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error updating size');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function removeItem(cartId, itemElement) {
        if (confirm('Are you sure you want to remove this item?')) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    itemElement.closest('.bag-item').remove();
                    updateCartCount();
                    updatePrices();
                } else {
                    alert('Error removing item');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    quantityControls.forEach(control => {
        const minusBtn = control.querySelector('.minus-btn');
        const plusBtn = control.querySelector('.plus-btn');
        const quantityValue = control.querySelector('.quantity-value');
        const cartId = minusBtn.dataset.cartId;

        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityValue.textContent);
            if (currentValue > 1) {
                updateQuantity(cartId, currentValue - 1, control);
            }
        });

        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityValue.textContent);
            if (currentValue < 10) {
                updateQuantity(cartId, currentValue + 1, control);
            }
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            removeItem(cartId, this);
        });
    });

    sizeSelects.forEach(select => {
        select.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const newSize = this.value;
            updateSize(cartId, newSize);
        });
    });

    function updatePrices() {
        fetch('get_cart_total.php')
            .then(response => response.json())
            .then(data => {
                const subtotalElement = document.querySelector('.subtotal-txt strong');
                if (subtotalElement) {
                    subtotalElement.innerHTML = `<i class="fa-solid fa-dollar-sign"></i>${data.total.toFixed(2)}`;
                }

                document.querySelectorAll('.bag-item').forEach(item => {
                    const quantityValue = item.querySelector('.quantity-value');
                    const priceElement = item.querySelector('.item-price');
                    const subtotalElement = item.querySelector('.item-subtotal');
                    
                    if (quantityValue && priceElement && subtotalElement) {
                        const price = parseFloat(priceElement.textContent.replace(/[^0-9.-]+/g, ''));
                        const quantity = parseInt(quantityValue.textContent);
                        const subtotal = price * quantity;
                        subtotalElement.innerHTML = `Subtotal: <i class="fa-solid fa-dollar-sign"></i>${subtotal.toFixed(2)}`;
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    }
}); 