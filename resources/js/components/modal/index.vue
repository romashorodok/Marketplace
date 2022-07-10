<template>
    <transition name="modal-fade">
        <div class="modal-backdrop">
            <div class="modal-wrapper">
                <section class="modal-header">
                    <slot name="modal-header">
                        <p class="modal-title">Modal title</p>
                    </slot>
                    <button
                        class="modal-close"
                        @click="$emit('close')"
                    >
                        x
                    </button>
                </section>
                <section class="modal-body">
                    <slot name="modal-body">
                        <p>Modal body</p>
                    </slot>
                </section>
                <section class="modal-footer">
                    <slot name="modal-footer">
<!--                        <p>Modal footer</p>-->
                    </slot>
                </section>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.modal-backdrop {
    right: 0;
    top: 0;
    background-color: rgba(0, 0, 0, 0.3);

    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-wrapper {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    border-radius: 10px;

    display: flex;
    flex-direction: column;

    width: 600px;
    height: 600px;

    overflow: hidden;
}

.modal-close {
    background: transparent;
    border: none;
}

.modal-footer {
    padding: 1.4rem;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity .4s ease-in-out;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

</style>
