<template>
    <div class="mobile-search-box d-lg-none">
        <div class="container">
            <div class="search-element max-width-100">
                <form action="#">
                    <input type="text" placeholder="Поиск">
                    <button><i class="pe-7s-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.mobile-search-box {
    background-color: $secondary;

    .search-element {
        max-width: 450px;
        margin: auto;

        @media #{$tablet-device, $large-mobile} {
            padding: 0 0 20px;
        }

        form {
            position: relative;

            input {
                height: 35px;
                width: 100%;
                background-color: $white;
                border: 0;
                border-radius: 5px;
                padding-right: 80px;
            }

            button {
                position: absolute;
                top: 0;
                right: 0;
                width: 55px;
                height: 35px;
                font-size: 25px;
                color: $white;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                border-radius: 0px 5px 5px 0px;
                background-color: $theme-color;
            }
        }

        &.max-width-100 {
            max-width: 100%;
        }
    }
}
</style>