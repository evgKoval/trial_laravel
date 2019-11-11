<template>
    <div>
        <div class="row mb-4">
            <template v-if="isSearch">
                    <b-col sm="3" class="mb-2">
                        <b-form-group
                            label="Min price"
                            label-for="input-price-min">
                            <b-form-input
                                type="number"
                                id="input-price-min"
                                v-model="price.min">
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
                                v-model="price.max">
                            </b-form-input>
                        </b-form-group>
                    </b-col> 
                    <b-col sm="3">
                        <b-form-group
                            label="Min date"
                            label-for="input-date-min">
                            <datepicker v-model="date.min" id="input-date-min" bootstrap-styling></datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col sm="3">
                        <b-form-group
                            label="Max date"
                            label-for="input-date-max">
                            <datepicker v-model="date.max" id="input-date-max" bootstrap-styling></datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col sm="3">
                        <b-form-group
                            label="Sort by"
                            label-for="input-date-max">
                            <b-form-select v-model="selected" :options="options"></b-form-select>
                        </b-form-group>
                    </b-col>
            </template>
        </div>
        <div class="row">
            <template v-if="!!filteredProducts.length">
                <div class="col-md-4" v-for="(product, index) in filteredProducts" :key="index">
                    <product :product="product" :userId="user" />
                </div>
            </template>
            <template v-else>
                <div class="col-12">
                    <h5>There are no products</h5>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: 'products',
        components: {
            Datepicker
        },
        props: {
            products: {
                type: Array
            },
            user: {
                type: Number
            },
            query: {
                type: String
            }
        },
        data() {
            return {
                isSearch: window.location.pathname == '/search' ? true : false,
                price: {
                    min: 1,
                    max: 1
                },
                date: {
                    min: null,
                    max: new Date()
                },
                options: ['all'],
                selected: 'all'
            }
        },
        computed: {
            filteredProducts() {
                let searched = [];

                if(this.query !== undefined) {
                    let query = '';

                    if (this.query !== null) {
                        query = this.query;
                    }

                    searched = this.products.filter((elem) => {
                        return elem.name.toLowerCase().includes(query.toLowerCase());
                    });

                    searched = searched.filter((elem) => {
                        return elem.price >= this.price.min && elem.price <= this.price.max
                    });

                    searched = searched.filter((elem) => {
                        let date = new Date(elem.created_at);

                        return date >= this.date.min && date <= this.date.max
                    });

                    searched = searched.filter((elem) => {
                        return this.selected == 'all' ? searched : this.selected == elem.category
                    });
                } else {
                    return this.products
                }

                return searched
            }
        },
        mounted() {
            this.price.max = Math.max.apply(Math, this.products.map((elem) => {
                return parseFloat(elem.price);
            }));

            this.date.min = Math.min.apply(Math, this.products.map((elem) => {
                return new Date(elem.created_at);
            }));

            this.date.min = new Date(this.date.min); 

            const unique = [...new Set(this.products.map(elem => elem.category))];

            this.options = this.options.concat(unique);
        }
    }
</script>
