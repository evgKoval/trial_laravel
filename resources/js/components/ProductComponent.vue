<template>
    <b-card
        :title="product.name"
        :img-src="product.img"
        img-alt="Image"
        img-top
        tag="article"
        class="mb-4"
    >
        <b-badge variant="info">{{ product.category }}</b-badge>

        <b-card-text>
            {{ product.price }}
        </b-card-text>

        <div class="d-flex align-items-center">
            <b-button variant="warning" class="mr-3" @click="addToCart(product.id)">Add to cart</b-button>
            <template v-if="userId == pId">
                <a @click="deleteFromWishlist(product.id)">
                    <custom-icon name="heart" base-class="custom-icon icon-active"></custom-icon>
                </a>
            </template>
            <template v-else>
                <a @click="addToWishlist(product.id)">
                    <custom-icon name="heart" base-class="custom-icon"></custom-icon>
                </a>
            </template>
        </div>

         <social-sharing url="https://trial-laravel.app/"
                         :title="product.name"
                         :quote="product.title"
                         :hashtags="product.category"
                          inline-template
                          class="mt-3">
            <div>
                <network network="facebook">
                    <font-awesome-icon :icon="['fab', 'facebook-square']" class="" />
                </network>
                <network network="telegram">
                    <font-awesome-icon :icon="['fab', 'telegram']" />
                </network>
                <network network="twitter">
                    <font-awesome-icon :icon="['fab', 'twitter-square']" />
                </network>
            </div>
        </social-sharing>

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
        },
        data() {
            return {
                pId: this.product.user_id
            }
        },
        methods: {
            addToWishlist(id) {
                axios.get('/add-to-wishlist/' + id);

                this.pId = this.userId;

                const wishlist = document.getElementsByClassName('wishlist')[0].innerText;
                let wlNumber = parseInt(wishlist.match(/\d/g).join(''), 10);
                wlNumber++;

                document.getElementsByClassName('wishlist')[0].innerText = wishlist.replace(wishlist.match(/\d+/g)[0], wlNumber.toString());
            },
            deleteFromWishlist(id) {
                axios.get('/delete-from-wishlist/' + id);

                this.pId = null;

                const wishlist = document.getElementsByClassName('wishlist')[0].innerText;
                let wlNumber = parseInt(wishlist.match(/\d/g).join(''), 10);
                wlNumber--;

                document.getElementsByClassName('wishlist')[0].innerText = wishlist.replace(wishlist.match(/\d+/g)[0], wlNumber.toString());
            },
            addToCart(id) {
                axios.get('/add-to-cart/' + id);

                const cart = document.getElementsByClassName('cart')[0].innerText;
                let cartNumber = parseInt(cart.match(/\d/g).join(''), 10);
                cartNumber++;

                document.getElementsByClassName('cart')[0].innerText = cart.replace(cart.match(/\d+/g)[0], cartNumber.toString());
            }
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

    .fa-facebook-square {
        color: #4267B2;
        font-size: 27px;
        margin-right: 0.5rem;
    }

    .fa-telegram {
        color: #0088cc;
        font-size: 27px;
        margin-right: 0.5rem;
    }

    .fa-twitter-square {
        color: #55ACEE;
        font-size: 27px;
    }
</style>
