<template>

    <button @click="toggleFriendshipStatus"
            class="btn btn-primary btn-block"
            v-text="getText"
    ></button>

</template>

<script>

    export default {

        props: {
            recipient: {
                Object,
                required: true
            },
            friendshipStatus: {
                type: String,
                required: true
            }
        },
        data() {

            return {

                localFriendshipStatus: this.friendshipStatus

            }

        },
        methods: {

            toggleFriendshipStatus() {

                let method = this.getMethod()

                axios[method](`friendships/${this.recipient.name}`)
                    .then(res => {

                        this.localFriendshipStatus = res.data.friendship_status

                    })
                    .catch(err => {

                        console.log(err.response.data)

                    })

            },
            getMethod() {

                return this.localFriendshipStatus === 'pending' ? 'delete' : 'post'

            }

        },
        computed: {

            getText() {

                if (this.localFriendshipStatus === 'pending') {

                    return 'Cancelar solicitud'

                }

                return 'Solicitar amistad'

            }

        }

    }

</script>