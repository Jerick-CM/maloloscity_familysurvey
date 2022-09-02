<script>
export default {
    setup(props, context) {},
};
</script>
<template>
    <footer>
        <div id="footer" class="p-5 bg-bottom">
            <div class="py-5">
                <p
                    class="text-white text-center text-lg md:text-2xl font-black text-mustard py-2"
                >
                    "DAKILA ANG BAYAN NA MAY MALASAKIT SA MAMAMAYAN"
                </p>
                <p
                    class="text-center text-md md:text-lg font-extrabold text-mustard"
                >
                    IGG. ABGDO. CHRISTIAN D. NATIVIDAD
                </p>
                <p
                    class="text-center text-sm md:text-md font-bold text-mustard"
                >
                    PUNONG LUNGSOD
                </p>
            </div>
        </div>
    </footer>
</template>
<style scoped>
.text-mustard {
    color: #e4c18e;
}
.bg-bottom {
    background: linear-gradient(
            0deg,
            rgba(158, 0, 0, 0.532),
            rgba(239, 68, 68, 0.701)
        ),
        url("../../assets/images/bg.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
footer {
    height: 100px;
    width: 100%;
    position: absolute;
    left: 0;
    bottom: 0;
}
</style>
