<template>
    <div>
        <b-tabs content-class="mt-3" justified>
            <b-tab title="Products" active>
                <b-button href="/admin/product-add/" variant="primary" class="mb-4 mt-4" block>Add a product</b-button>
                <div class="row mb-4 mt-4">
                    <b-col sm="12">
                        <b-form-input id="input-large" placeholder="Search products" :value="searchProducts" @change.native="searchProducts = $event.target.value"></b-form-input>
                    </b-col>
                </div>
                <div class="row mb-4">
                    <b-col sm="3" class="mb-2">
                        <b-form-group
                            label="Min price"
                            label-for="input-price-min">
                            <b-form-input
                                type="number"
                                id="input-price-min"
                                v-model="priceProducts.min">
                            </b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col sm="3" class="mb-2">
                        <b-form-group
                            label="Max price"
                            label-for="input-price-max">
                            <b-form-input
                                type="number"
                                id="input-price-max"
                                v-model="priceProducts.max">
                            </b-form-input>
                        </b-form-group>
                    </b-col> 
                    <b-col sm="3" class="mb-2">
                        <b-form-group
                            label="Min date"
                            label-for="input-date-min">
                            <datepicker v-model="dateProducts.min" id="input-date-min" bootstrap-styling></datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col sm="3" class="mb-2">
                        <b-form-group
                            label="Max date"
                            label-for="input-date-max">
                            <datepicker v-model="dateProducts.max" id="input-date-max" bootstrap-styling></datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col sm="3">
                        <b-form-group
                            label="Sort by"
                            label-for="input-date-max">
                            <b-form-select v-model="selectedProducts" :options="optionsProducts"></b-form-select>
                        </b-form-group>
                    </b-col>
                </div>
                <template v-if="!!products.length">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Id</th>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="product in productsFiltered">
                        <td>{{ product.id }}</td>
                        <td>
                            <img v-if="product.img != 0" :src="product.img" :alt="product.name" width="50%">
                        </td>
                        <td>{{ product.name }}</td>
                        <td>${{ product.price }}</td>
                        <td>
                            <b-button :href="'/admin/product-edit/' + product.id" variant="warning" class="mr-2">Edit</b-button>
                            <b-button variant="danger" @click="deleteProduct(product.id)">Delete</b-button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                    <h5>There are no products</h5>
                </template>
            </b-tab>
            <b-tab title="Orders">
                <div class="row mb-4 mt-4">
                    <b-col sm="12">
                        <b-form-input id="input-large" placeholder="Search orders" v-model="searchOrders"></b-form-input>
                    </b-col>
                </div>
                <div class="row mb-4">
                    <b-col sm="4">
                        <b-form-group
                            label="Status"
                            label-for="input-date-max">
                            <b-form-select v-model="selectedOrders" :options="optionsOrders"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col sm="4">
                        <b-form-group
                            label="Min date"
                            label-for="input-date-min">
                            <datepicker v-model="dateOrders.min" id="input-date-min" bootstrap-styling></datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col sm="4">
                        <b-form-group
                            label="Max date"
                            label-for="input-date-max">
                            <datepicker v-model="dateOrders.max" id="input-date-max" bootstrap-styling></datepicker>
                        </b-form-group>
                    </b-col>
                </div>
                <template v-if="!!products.length">
                  <table id="table-orders" class="table">
                    <thead>
                      <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">City</th>
                        <th scope="col">Adress</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Created</th>
                        <th scope="col" class="text-right">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(order, index) in ordersFiltered">
                        <td>
                            {{ order.id }}
                        </td>
                        <td>
                            {{ order.name }}
                        </td>
                        <td>{{ order.city }}</td>
                        <td>{{ order.adress }}</td>
                        <td>{{ order.phone == 0 ? 'There is no phone' : order.phone }}</td>
                        <td>{{ order.created_at }}</td>
                        <td class="text-right">
                            <b-button size="sm" :variant="getStatus(index).variant" :id="'exPopoverReactive' + index">{{ getStatus(index).title }}</b-button>

                            <b-popover
                             :target="'exPopoverReactive' + index"
                             triggers="click"
                             placement="auto"
                             container="myContainer"
                             ref="popover"
                           >
                             <template slot="title">
                               <b-btn class="close" aria-label="Close" @click="onClose">
                                 <span class="d-inline-block" aria-hidden="true">&times;</span>
                               </b-btn>Change status
                             </template>
                             <div>
                               <b-button
                                 variant="warning" block @click="changeStatus(order.id, 0)"
                               >In process</b-button>
                               <b-button
                                 variant="success" block @click="changeStatus(order.id, 1)"
                               >Completed</b-button>
                               <b-button
                                 variant="danger" block @click="changeStatus(order.id, 2)"
                               >Canceled</b-button>
                             </div>
                           </b-popover>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                    <h5>There are no products</h5>
                </template>
            </b-tab>
        </b-tabs>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: 'orders',
        props: {
            products: {
                type: Array
            },
            orders: {
                type: Array
            }
        },
        components: {
            Datepicker
        },
        data() {
            return {
                productsForTable: this.products,
                ordersForTable: this.orders,
                searchProducts: '',
                priceProducts: {
                    min: 1,
                    max: 1
                },
                dateProducts: {
                    min: null,
                    max: new Date()
                },
                optionsProducts: ['All'],
                selectedProducts: 'All',
                searchOrders: '',
                dateOrders: {
                    min: null,
                    max: new Date()
                },
                optionsOrders: ['All'],
                selectedOrders: 'All'
            }
        },
        asyncComputed: {
            async productsFiltered() {
                let searched = [];

                if (this.searchProducts != '') {
                    await this.axiosProducts().then((res) => {
                        searched = res;
                    });
                } else {
                    searched = this.productsForTable;
                }

                searched = searched.filter((elem) => {
                    return elem.price >= this.priceProducts.min && elem.price <= this.priceProducts.max
                });

                searched = searched.filter((elem) => {
                    let date = new Date(elem.created_at);

                    return date >= this.dateProducts.min && date <= this.dateProducts.max
                });

                searched = searched.filter((elem) => {
                    return this.selectedProducts == 'All' ? searched : this.selectedProducts == elem.category
                });

                return searched
            }
        },
        computed: {
            ordersFiltered() {
                let searched = [];

                searched = this.ordersForTable.filter(item => item.name.toLowerCase().includes(this.searchOrders.toLowerCase()) || item.city.toLowerCase().includes(this.searchOrders.toLowerCase()) || item.adress.toLowerCase().includes(this.searchOrders.toLowerCase()) || item.phone.toLowerCase().includes(this.searchOrders.toLowerCase()) || item.created_at.toLowerCase().includes(this.searchOrders.toLowerCase()) || item.id == this.searchOrders);

                searched = searched.filter((item) => {
                    let date = new Date(item.created_at);

                    return date >= this.dateOrders.min && date <= this.dateOrders.max
                });

                searched = searched.filter((item) => {
                    return this.selectedOrders == 'All' ? searched : this.selected == item.category
                });

                return searched;
            }
        },
        methods: {
            axiosProducts() {
                return axios.get('/search-products/' + this.searchProducts).then((res) => {
                    return res.data;
                });
            },
            deleteProduct(id) {
                axios.delete('/admin/' + id).then(() => {
                    const index = this.productsForTable.findIndex(product => product.id === id);
                    this.productsForTable.splice(index, 1);
                });
            },
            onClose() {
                this.$root.$emit('bv::hide::popover');
            },
            changeStatus(id, status) {
                axios.put('/change-status/' + id + '/' + status).then(() => {
                    const index = this.ordersForTable.findIndex(order => order.id === id);
                    this.ordersForTable[index].status = status;
                });
            },
            getStatus(id) {
                return {
                    variant: this.ordersForTable[id].status == 0 ? 'warning' : this.ordersForTable[id].status == 1 ? 'success' : 'danger',
                    title: this.ordersForTable[id].status == 0 ? 'In process'
                        : this.ordersForTable[id].status == 1 ? 'Completed'
                        : 'Canceled'
                }
            }
        },
        mounted() {
            this.dateOrders.min = Math.min.apply(Math, this.ordersForTable.map((elem) => {
                return new Date(elem.created_at);
            }));

            this.dateOrders.min = new Date(this.dateOrders.min); 

            const uniqueOrders = [...new Set(this.ordersForTable.map((elem) => {
                return elem.status == 0 ? 'In process' : elem.status == 1 ? 'Completed' : 'Canceled';
            }))];

            this.optionsOrders = this.optionsOrders.concat(uniqueOrders);



            this.priceProducts.max = Math.max.apply(Math, this.productsForTable.map((elem) => {
                return parseFloat(elem.price);
            }));

            this.dateProducts.min = Math.min.apply(Math, this.productsForTable.map((elem) => {
                return new Date(elem.created_at);
            }));

            this.dateProducts.min = new Date(this.dateProducts.min); 

            const uniqueProducts = [...new Set(this.productsForTable.map(elem => elem.category))];

            this.optionsProducts = this.optionsProducts.concat(uniqueProducts);
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
    .popover-container input {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>