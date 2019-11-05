<template>
    <div class="row">
        <div class="col-md-4" v-for="(product, index) in products" :key="index">
            <product :product="product" />
        </div>
    </div>
</template>

<script>
    import products from '../../../products.json';

    export default {
        name: 'products',
        data() {
            return {
                products
            };
        }
    }
</script>
