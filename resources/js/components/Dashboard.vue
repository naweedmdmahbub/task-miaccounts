<template>
    <div class="warning-wrapper">
        <div class="content-wrapper">
            <h1>This is the dashboard</h1>
        </div>
    </div>
</template>

<script>
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
