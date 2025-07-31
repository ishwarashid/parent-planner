<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'title' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'title' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{ show: false, name: '<?php echo e($name); ?>' }"
    x-show="show"
    x-on:open-modal.window="show = ($event.detail === name)"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
>
    <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold"><?php echo e($title); ?></h3>
            <button @click="show = false" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <div class="p-6">
            <?php echo e($slot); ?>

        </div>
    </div>
</div><?php /**PATH /home/azan/Desktop/parent-planner-master/resources/views/components/modal.blade.php ENDPATH**/ ?>