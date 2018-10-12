<template>

    <div>

        <div v-if="localFriendshipStatus === 'pending'">

            <span v-text="sender.name"></span> has sent you a Friend Request!

            <button @click="acceptFriendshipRequest">Accept request</button>
            <button dusk="deny-friendship" @click="denyFriendshipRequest">Deny request</button>

        </div>

        <div v-else-if="localFriendshipStatus === 'accepted'">

            You are now friends with <span v-text="sender.name"></span> !

        </div>

        <div v-else-if="localFriendshipStatus === 'denied'">

            Request denied <span v-text="sender.name"></span>

        </div>

        <div v-if="localFriendshipStatus === 'deleted'">Request deleted</div>
        <button v-else dusk="delete-friendship" @click="deleteFriendship">Delete friendship</button>

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

            acceptFriendshipRequest() {

                axios.post(`/accept-friendships/${this.sender.name}`)

                    .then(res => {

                        this.localFriendshipStatus = res.data.friendship_status

                    })
                    .catch(err => {

                        console.log(err.response.data)

                    })

            },
            denyFriendshipRequest() {

                axios.delete(`/accept-friendships/${this.sender.name}`)

                    .then(res => {

                        this.localFriendshipStatus = res.data.friendship_status

                    })
                    .catch(err => {

                        console.log(err.response.data)

                    })

            },
            deleteFriendship() {

                axios.delete(`/friendships/${this.sender.name}`)

                    .then(res => {

                        this.localFriendshipStatus = res.data.friendship_status

                    })
                    .catch(err => {

                        console.log(err.response.data)

                    })

            },

        }

    }

</script>