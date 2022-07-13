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
                    <slot name="modal-footer"></slot>
                </section>
            </div>
        </div>
    </transition>
</template>
