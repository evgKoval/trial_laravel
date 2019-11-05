<template>
    <b-card
        :title="product.name"
        :img-src="product.img"
        img-alt="Image"
        img-top
        tag="article"
        class="mb-4"
    >
        <b-card-text>
            {{ product.price }}
        </b-card-text>

        <b-button href="#" variant="warning">Add to cart</b-button>
    </b-card>
</template>

<script>
    export default {
        name: 'product',
        props: {
            product: {
                type: Object
            }
        }
    }
</script>
