<template>
    <div id="offcanvas-cart" class="offcanvas offcanvas-cart">
        <div class="inner">
            <div class="head">
                <span class="title">Cart</span>
                <button class="offcanvas-close">×</button>
            </div>
            <div class="body customScroll">
                <ul class="minicart-product-list">
                    <li>
                        <a href="single-product.html" class="image"><img src=""
                                alt="Cart product Image"></a>
                        <div class="content">
                            <a href="single-product.html" class="title">Modern Smart Phone</a>
                            <span class="quantity-price">1 x <span class="amount">$18.86</span></span>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                    <li>
                        <a href="single-product.html" class="image"><img src=""
                                alt="Cart product Image"></a>
                        <div class="content">
                            <a href="single-product.html" class="title">Bluetooth Headphone</a>
                            <span class="quantity-price">1 x <span class="amount">$43.28</span></span>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                    <li>
                        <a href="single-product.html" class="image"><img src=""
                                alt="Cart product Image"></a>
                        <div class="content">
                            <a href="single-product.html" class="title">Smart Music Box</a>
                            <span class="quantity-price">1 x <span class="amount">$37.34</span></span>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="foot">
                <div class="buttons mt-30px">
                    <a href="cart.html" class="btn btn-dark btn-hover-primary mb-30px">view cart</a>
                    <a href="checkout.html" class="btn btn-outline-dark current-btn">checkout</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted(() => {
  // Get cart-specific elements
  const cartOffcanvas = document.getElementById('offcanvas-cart');
  const offCanvasOverlay = document.querySelector(".offcanvas-overlay");
  
  // Open cart when clicked (handle both href and class selectors)
  const cartToggles = document.querySelectorAll('a[href="#offcanvas-cart"], .offcanvas-toggle[href="#offcanvas-cart"]');
  cartToggles.forEach(toggle => {
    toggle.addEventListener("click", function(e) {
      e.preventDefault();
      document.body.classList.add("offcanvas-open");
      cartOffcanvas.classList.add("offcanvas-open");
      offCanvasOverlay.style.display = "block";
    });
  });

  // Close cart when clicked
  const closeButton = cartOffcanvas.querySelector('.offcanvas-close');
  closeButton.addEventListener("click", function(e) {
    e.preventDefault();
    document.body.classList.remove("offcanvas-open");
    cartOffcanvas.classList.remove("offcanvas-open");
    offCanvasOverlay.style.display = "none";
  });
});
</script>