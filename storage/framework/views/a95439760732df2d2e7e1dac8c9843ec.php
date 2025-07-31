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
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome, <?php echo e(Auth::user()->name); ?>!</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Next Visit Card -->
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-blue-800">Next Visitation</h4>
                        <?php if($nextVisit): ?>
                            <p class="text-2xl font-bold text-blue-900"><?php echo e(\Carbon\Carbon::parse($nextVisit->date_start)->diffForHumans()); ?></p>
                            <p class="text-sm text-blue-700"><?php echo e($nextVisit->child->name); ?> on <?php echo e(\Carbon\Carbon::parse($nextVisit->date_start)->format('M d, Y H:i A')); ?></p>
                            <a href="<?php echo e(route('visitations.show', $nextVisit)); ?>" class="text-blue-600 hover:text-blue-800 text-sm mt-2 block">View Details</a>
                        <?php else: ?>
                            <p class="text-gray-600">No upcoming visitations.</p>
                            <a href="<?php echo e(route('visitations.create')); ?>" class="text-blue-600 hover:text-blue-800 text-sm mt-2 block">Add a Visitation</a>
                        <?php endif; ?>
                    </div>

                    <!-- Pending Expenses Card -->
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-yellow-800">Pending Expenses</h4>
                        <?php if($pendingExpenses->count() > 0): ?>
                            <p class="text-2xl font-bold text-yellow-900"><?php echo e($pendingExpenses->count()); ?></p>
                            <p class="text-sm text-yellow-700">Total: $<?php echo e(number_format($pendingExpenses->sum('amount'), 2)); ?></p>
                            <a href="<?php echo e(route('expenses.index')); ?>" class="text-yellow-600 hover:text-yellow-800 text-sm mt-2 block">View All Pending</a>
                        <?php else: ?>
                            <p class="text-gray-600">No pending expenses.</p>
                            <a href="<?php echo e(route('expenses.create')); ?>" class="text-yellow-600 hover:text-yellow-800 text-sm mt-2 block">Add an Expense</a>
                        <?php endif; ?>
                    </div>

                    <!-- Upcoming Birthdays Card -->
                    <div class="bg-purple-100 p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-purple-800">Upcoming Birthdays</h4>
                        <?php if($childrenWithUpcomingBirthdays->count() > 0): ?>
                            <ul class="list-disc list-inside text-sm text-purple-700">
                                <?php $__currentLoopData = $childrenWithUpcomingBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($child->name); ?> (<?php echo e(\Carbon\Carbon::parse($child->dob)->format('M d')); ?>) - <?php echo e(\Carbon\Carbon::parse($child->dob)->diffForHumans(null, true, false, 1)); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <a href="<?php echo e(route('children.index')); ?>" class="text-purple-600 hover:text-purple-800 text-sm mt-2 block">View Children</a>
                        <?php else: ?>
                            <p class="text-gray-600">No upcoming birthdays.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Activity (Optional - can be added later) -->
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner" style="min-height: 150px;">
                    <p class="text-center text-gray-500">Recent activities (e.g., new visitations, expenses, documents) can be displayed here.</p>
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
<?php endif; ?><?php /**PATH /home/azan/Desktop/parent-planner-master/resources/views/dashboard.blade.php ENDPATH**/ ?>