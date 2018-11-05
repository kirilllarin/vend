<template>
    <div v-if="favoritesFetched">
        <span class="header-subtitle">{{ $t('favorites.title') }}</span>
        <div v-if="favorites.length == 0">
            {{ $t('favorites.empty') }}
        </div>
        <div @click.prevent="showFavorite(favorite)" v-for="favorite in favorites" class="media media-sm small media-dark cursor-pointer">
            <div class="media-left">
                <i :class="getFavoriteIcon(favorite)"></i>
            </div>
            <div class="media-body">
                {{ favorite.favoritable.title }}
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    const typeRouteMapping = {
        topics: 'topic',
        projects: 'project',
    }

    export default {
        computed: {
            ...mapGetters({
                favorites: 'favorites',
                favoritesFetched: 'favoritesFetched'
            })
        },
        mounted() {
            this.$store.dispatch('getFavorites')
        },
        methods: {
            getFavoriteIcon(favorite) {
                let icon = ''
                switch(favorite.type) {
                    case 'projects' : icon = 'icon-stack'; break;
                    case 'topics' : icon = 'icon-bubbles'; break;
                }
                return icon
            },
            showFavorite(favorite) {
                if(favorite.type === 'projects' || favorite.type === 'topics') {
                    const name = typeRouteMapping[favorite.type]
                    this.$router.push({name: name, params: {id: favorite.favoritable.id}})
                }
            }
        }
    }
</script>
