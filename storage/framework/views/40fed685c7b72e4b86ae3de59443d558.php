<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Visitation Calendar')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <meta name="user-id" content="<?php echo e($currentUserId); ?>">
    <meta name="visitations-api-url" content="<?php echo e(route('visitations.api')); ?>">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Upcoming Visitations</h3>
                        <div class="flex items-center space-x-4">
                            <div>
                                <label for="child_filter" class="block text-sm font-medium text-gray-700">Filter by Child:</label>
                                <select id="child_filter" name="child_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All Children</option>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($child->id); ?>"><?php echo e($child->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button x-data @click="$dispatch('open-modal', { name: 'visitation-form' })" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add New Visitation
                            </button>
                        </div>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'visitation-form','title' => 'Visitation Details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'visitation-form','title' => 'Visitation Details']); ?>
                        <form id="visitationForm">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="visitation_id" name="visitation_id">

                            <!-- Child -->
                            <div>
                                <label for="child_id" class="block text-sm font-medium text-gray-700">Child</label>
                                <select id="child_id" name="child_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($child->id); ?>"><?php echo e($child->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Date Start -->
                            <div class="mt-4">
                                <label for="date_start" class="block text-sm font-medium text-gray-700">Start Time</label>
                                <input type="datetime-local" id="date_start" name="date_start" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Date End -->
                            <div class="mt-4">
                                <label for="date_end" class="block text-sm font-medium text-gray-700">End Time</label>
                                <input type="datetime-local" id="date_end" name="date_end" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Is Recurring -->
                            <div class="mt-4 flex items-center">
                                <input id="is_recurring" name="is_recurring" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-700">Is Recurring?</label>
                            </div>

                            <!-- Notes -->
                            <div class="mt-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <button type="button" x-on:click="$dispatch('close-modal')" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Save Visitation
                                </button>
                            </div>
                        </form>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-8">
                        <!-- Placeholder for FullCalendar.js or Livewire Calendar -->
                        <div id="calendar" class="bg-gray-50 p-4 rounded-lg shadow-inner" style="min-height: 500px;">
                            <div class="flex justify-center items-center h-full">
                                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Visitation List</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Child
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Parent
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Start Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        End Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recurring
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Notes
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $visitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($visitation->child->name); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e($visitation->parent->name); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($visitation->date_start)->format('M d, Y H:i A')); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($visitation->date_end)->format('M d, Y H:i A')); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e($visitation->is_recurring ? 'Yes' : 'No'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo e($visitation->notes ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="<?php echo e(route('visitations.show', $visitation)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                            <a href="<?php echo e(route('visitations.edit', $visitation)); ?>" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
                                            <form action="<?php echo e(route('visitations.destroy', $visitation)); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this visitation?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No visitations found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/azan/Desktop/parent-planner-master/resources/views/visitations/calendar.blade.php ENDPATH**/ ?>