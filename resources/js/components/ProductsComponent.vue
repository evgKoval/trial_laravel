<template>
    <div class="row">
        <template v-if="isSearch">
            <b-col sm="3" class="mb-4">
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
            <b-col sm="3" class="mb-4">
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
        </template>
        <template v-if="!!products.length">
            <div class="col-md-4" v-for="(product, index) in products" :key="index">
                <product :product="product" />
            </div>
        </template>
        <template v-else>
            <div class="col-12">
                <h5>There are no products</h5>
            </div>
        </template>
        
    </div>
</template>

<script>
    import products from '../../../products.json';
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: 'products',
        components: {
            Datepicker
        },
        props: {
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
                }
            }
        },
        computed: {
            products() {
                let searched = [];

                if(this.query !== undefined) {
                    let query = '';

                    if (this.query !== null) {
                        query = this.query;
                    }

                    searched = products.filter((elem) => {
                        return elem.name.toLowerCase().includes(query.toLowerCase());
                    });

                    searched = searched.filter((elem) => {
                        const price = elem.price.slice(1).replace(',', '');

                        return parseFloat(price) >= this.price.min && parseFloat(price) <= this.price.max
                    });

                    searched = searched.filter((elem) => {
                        let date = JSON.parse(elem.date);

                        date = new Date(date);

                        return date >= this.date.min && date <= this.date.max
                    });
                } else {
                    return products
                }

                return searched
            }
        },
        mounted() {
            this.price.max = Math.max.apply(Math, products.map((elem) => {
                const price = elem.price.slice(1).replace(',', '');
                return parseFloat(price);
            }));

            this.date.min = Math.min.apply(Math, products.map((elem) => {
                const date = JSON.parse(elem.date);
                return new Date(date);
            }));

            this.date.min = new Date(this.date.min);
        }
    }
</script>
