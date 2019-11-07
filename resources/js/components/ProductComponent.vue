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

        <div class="d-flex align-items-center">
            <b-button :href="'/add-to-cart/' + product.id" variant="warning" class="mr-3">Add to cart</b-button>
            <template v-if="userId == product.user_id">
                <a :href="'delete-from-wishlist/' + product.id">
                    <custom-icon name="heart" base-class="custom-icon icon-active"></custom-icon>
                </a> 
            </template>
            <template v-else>
                <a :href="'add-to-wishlist/' + product.id">
                    <custom-icon name="heart" base-class="custom-icon"></custom-icon>
                </a> 
            </template>
        </div>
            

    </b-card>
</template>

<script>
    import customIcon from 'vue-icon/lib/vue-feather.esm';

    export default {
        name: 'product',
        props: {
            product: {
                type: Object
            },
            userId: {
               type: Number 
            }
        },
        components: {
            customIcon
        }
    }
</script>

<style>
    .custom-icon {
        width: 24px;
        stroke: grey;
        transition: 0.18s;
    }

    .custom-icon:hover {
        stroke: red;
    }

    .icon-active {
        fill: red;
        stroke: red;
    }
</style>
