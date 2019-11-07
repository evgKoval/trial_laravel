<template>
    <div>
        <template v-if="!!products.length">
          <table class="table">
            <thead>
              <tr>
                <th scope="col"></th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products">
                <td>
                    <img :src="product.img" :alt="product.name">
                </td>
                <td>{{ product.name }}</td>
                <td>{{ product.price }}</td>
                <td>
                    <a :href="'delete-from-cart/' + product.id">
                        <custom-icon name="x" base-class="custom-icon"></custom-icon>
                    </a> 
                </td>
              </tr>
            </tbody>
          </table>

          <div class="row">
              <b-col sm="10">
                  <h3>Total</h3>
              </b-col>
              <b-col sm="2" class="text-right">
                  <h3>${{ amount }}</h3>
              </b-col>
          </div>
        </template>
        <template v-else>
          <h5>Your cart is empty</h5>
        </template>
        
        <div class="row mt-5">
            <b-col sm="6">
                <b-button href="/" variant="secondary">Back to shoping</b-button>
            </b-col>
            <b-col sm="6" class="text-right">
                <b-button href="/checkout" v-if="!!products.length" variant="success">Proceed to checkout</b-button>
            </b-col>
        </div>
    </div>
</template>

<script>
    import customIcon from 'vue-icon/lib/vue-feather.esm';

    export default {
        name: 'orders',
        props: {
            products: {
                type: Array
            }
        },
        components: {
            customIcon
        },
        data() {
            return {
                fields: ['name', 'price']
            }
        },
        computed: {
            amount() {
                let amount = 0;

                this.products.forEach((elem) => {
                    const price = elem.price.slice(1).replace(',', '');
                    amount += parseFloat(price);
                });

                return amount
            }
        }
    }
</script>