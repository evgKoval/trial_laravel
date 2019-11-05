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
        props: {
            query: {
                type: String
            }
        },
        computed: {
            products() {
                console.log(this.query);

                let searched = [];

                if (this.query) {
                    searched = products.filter((elem) => {
                        return elem.name.toLowerCase().includes(this.query.toLowerCase());
                    })
                } else {
                    return products
                }

                return searched
            }
        },
        mounted() {
            
            // if (this.query) {
            //     const arr = this.products.filter((elem) => {
            //         console.log(elem.name.toLowerCase().includes(this.query));
            //         return elem.name.toLowerCase().includes(this.query.toLowerCase());
            //     })
            //     console.log(arr);
            // }
        }
    }
</script>
