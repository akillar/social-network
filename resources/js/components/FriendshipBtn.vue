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

                this.redirectIfGuest()

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

                if (this.localFriendshipStatus === 'pending' ||
                    this.localFriendshipStatus === 'accepted') {

                    return  'delete'

                }

                return 'post'

            }

        },
        computed: {

            getText() {

                if (this.localFriendshipStatus === 'pending') {

                    return 'Cancel request'

                }

                if (this.localFriendshipStatus === 'accepted') {

                    return 'Delete friend'

                }

                if (this.localFriendshipStatus === 'denied') {

                    return 'Denied request'

                }

                return 'Request friendship'

            }

        }

    }

</script>