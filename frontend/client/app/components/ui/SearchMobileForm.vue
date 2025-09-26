<template>
    <div class="mobile-search-box d-lg-none">
        <div class="container">
            <UiSearchForm />
        </div>
    </div>
</template>

<style scoped lang="scss">
.mobile-search-box {
    background-color: $secondary;
    padding: 15px 0;
    position: relative;

    .search-element {
        max-width: 100%;
        margin: auto;
        position: relative;
    }
}
</style>