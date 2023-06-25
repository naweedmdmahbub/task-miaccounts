<template>
    <div class="warning-wrapper">
        <div class="content-wrapper">
            <img :src="warningImg" alt="This is the 404 image">
            <h1>Page not Found</h1>
        </div>
    </div>
</template>

<script>
    // import warningImg from '../components/image/404img.jpg'
    import warningImg from '../components/Images/404img2.png'

    export default {
        data() {
            return {
                warningImg: warningImg
            }
        }
    }
</script>

<style scoped>
.warning-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.content-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.content-wrapper img {
    max-width: 100%;
    height: auto; /* Maintain the aspect ratio of the image */
    margin-bottom: 16px; /* Add margin below the image */
}
</style>
