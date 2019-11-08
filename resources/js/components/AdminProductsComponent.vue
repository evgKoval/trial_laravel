<template>
    <div>
        <b-tabs content-class="mt-3" justified>
            <b-tab title="Products" active>
                <template v-if="!!products.length">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="product in productsFiltered">
                        <td>
                            <img :src="product.img" :alt="product.name" width="50%">
                        </td>
                        <td>{{ product.name }}</td>
                        <td>{{ product.price }}</td>
                        <td>
                            <b-button variant="warning" class="mr-2">Edit</b-button>
                            <b-button variant="danger">Delete</b-button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                    <h5>There are no products</h5>
                </template>
            </b-tab>
            <b-tab title="Orders"><p>I'm the second tab</p></b-tab>
        </b-tabs>
    </div>
</template>

<script>
    export default {
        name: 'orders',
        props: {
            products: {
                type: Array
            }
        },
        data() {
            return {
                productForTable: this.products,
            }
        },
        computed: {
            productsFiltered() {
                return this.productForTable
            }
        },
        methods: {
            delete(id) {
                //
            }
        }
    }
</script>

<style>
    tbody {
          display:block;
          max-height:450px;
          overflow-y:auto;
      }
      thead, tbody tr {
          display:table;
          width:100%;
          table-layout:fixed;
      }
      thead {
          width: calc( 100% - 1em )
      } 
</style>