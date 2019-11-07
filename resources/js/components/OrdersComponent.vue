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
              <tr v-for="product in productsFiltered">
                <td>
                    <img :src="product.img" :alt="product.name">
                </td>
                <td>{{ product.name }}</td>
                <td>{{ product.price }}</td>
                <td>
                    <a @click="deleteFromCart(product.id)">
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
                productForTable: this.products,
                fields: ['name', 'price']
            }
        },
        computed: {
            productsFiltered() {
                return this.productForTable
            },
            amount() {
                let amount = 0;

                this.productForTable.forEach((elem) => {
                    const price = elem.price.slice(1).replace(',', '');
                    amount += parseFloat(price);
                });

                return amount
            }
        },
        methods: {
          deleteFromCart(id) {
              axios.get('/delete-from-cart/' + id);

              const index = this.productForTable.findIndex(product => product.id === id);
              this.productForTable.splice(index, 1);
              console.log(index)
              console.log(this.productForTable);

              const cart = document.getElementsByClassName('cart')[0].innerText;
              let cartNumber = parseInt(cart.match(/\d/g).join(''), 10);
              cartNumber--;

              document.getElementsByClassName('cart')[0].innerText = cart.replace(cart.match(/\d+/g)[0], cartNumber.toString());

          }
        }
    }
</script>