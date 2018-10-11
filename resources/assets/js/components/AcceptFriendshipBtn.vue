<template>

    <div v-if="localFriendshipStatus === 'pending'">

        <span v-text="sender.name"></span> has sent you a Friend Request!

        <button @click="acceptFriendshipRequest">Accept request</button>

    </div>

    <div v-else>

        You are now friends with <span v-text="sender.name"></span> !

    </div>

</template>

<script>

    export default {

        props: {

            sender: {
                type: Object,
                required: true
            },
            friendshipStatus: {
                type: Object,
                required: true
            }

        },
        data() {

            return {

                localFriendshipStatus: this.friendshipStatus

            }

        },
        methods: {

            acceptFriendshipRequest() {

                axios.post(`/accept-friendships/${this.sender.name}`)

                    .then(res => {

                        this.localFriendshipStatus = 'accepted'

                    })
                    .catch(err => {

                        console.log(err.response.data)

                    })

            }

        }

    }

</script>