<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one if none exists
        $adminUser = User::where('is_admin', true)->first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create sample blog posts
        Blog::create([
            'title' => 'The Benefits of Co-Parenting Apps',
            'slug' => 'benefits-of-co-parenting-apps',
            'excerpt' => 'Discover how technology can improve communication and coordination between co-parents.',
            'content' => "Co-parenting can be challenging, especially when you're navigating the complexities of raising children with someone you're no longer romantically involved with. However, technology has made it easier than ever to stay connected and organized when it comes to your children's needs.

Co-parenting apps offer a centralized platform where both parents can access important information about their children's schedules, activities, and well-being. These apps often include features such as shared calendars, messaging systems, expense tracking, and document storage.

One of the biggest benefits of co-parenting apps is improved communication. Instead of relying on text messages or phone calls that can be easily misunderstood or lost in a sea of other communications, co-parenting apps provide a dedicated space for parents to discuss their children's needs. This can help reduce conflict and ensure that both parents are on the same page when it comes to important decisions.

Another advantage is the ability to keep all relevant information in one place. From school schedules to medical appointments to extracurricular activities, co-parenting apps allow both parents to stay informed about their children's lives. This can be especially helpful when coordinating pickups, sharing important updates, or planning for upcoming events.

In conclusion, co-parenting apps can be a valuable tool for parents who want to maintain a positive and collaborative relationship for the sake of their children. By providing a centralized platform for communication and organization, these apps can help reduce stress and confusion while ensuring that both parents are actively involved in their children's lives.",
            'published' => true,
            'published_at' => now(),
            'user_id' => $adminUser->id,
        ]);

        Blog::create([
            'title' => '5 Essential Tools for Modern Parents',
            'slug' => 'essential-tools-for-modern-parents',
            'excerpt' => 'Explore the must-have tools that can simplify your parenting journey and reduce stress.',
            'content' => "Modern parenting comes with its own set of challenges, but thankfully, there are numerous tools and technologies available to help make the journey a bit easier. Here are 5 essential tools that every modern parent should consider:

1. Digital Calendars: Coordinating family schedules, school events, and extracurricular activities can be overwhelming. Digital calendars like Google Calendar or Apple Calendar allow you to share events with your co-parent and set reminders to ensure nothing falls through the cracks.

2. Shared To-Do Lists: Apps like Todoist or Any.do allow families to create shared shopping lists, household chores, and other tasks. This promotes teamwork and ensures that everyone knows their responsibilities.

3. Parenting Apps: From baby tracking apps that monitor feeding and sleeping schedules to apps that help with potty training, there's an app for almost every parenting challenge. These tools can provide valuable insights and help you stay consistent with routines.

4. Digital Photo Albums: Instead of physical photo albums that can be lost or damaged, digital platforms like Google Photos or Shutterfly allow you to store and share precious memories with family members near and far.

5. Online Learning Resources: With the rise of educational apps and online learning platforms, parents can supplement their children's education with interactive and engaging content. From math games to reading apps, these resources can make learning fun and accessible.

By incorporating these tools into your parenting routine, you can reduce stress, improve organization, and create a more harmonious family environment.",
            'published' => true,
            'published_at' => now()->subDays(2),
            'user_id' => $adminUser->id,
        ]);

        Blog::create([
            'title' => 'Managing Finances After Divorce',
            'slug' => 'managing-finances-after-divorce',
            'excerpt' => 'Learn effective strategies for handling shared expenses and financial responsibilities.',
            'content' => "Divorce can be a challenging time, especially when it comes to managing finances and shared expenses for your children. However, with careful planning and clear communication, you can establish a system that works for both parents and ensures your children's needs are met.

Here are some strategies for managing finances after divorce:

1. Separate Finances: One of the first steps after divorce is to establish separate bank accounts and credit cards. This helps prevent future financial entanglements and ensures that each parent is responsible for their own expenses.

2. Create a Shared Expense Account: For expenses that both parents are responsible for, consider setting up a joint account specifically for these costs. This could include child support, school fees, medical expenses, and extracurricular activities.

3. Document Everything: Keep detailed records of all financial transactions related to your children. This includes receipts, bank statements, and any written agreements about shared expenses. This documentation can be crucial if disputes arise in the future.

4. Use Technology: Financial apps and co-parenting platforms can help you track shared expenses, split costs, and maintain financial transparency. These tools can automatically calculate each parent's share and provide a clear record of all transactions.

5. Review and Adjust Regularly: Financial circumstances can change over time, so it's important to review your arrangements regularly. This might involve adjusting child support payments, revising expense-sharing agreements, or updating your budget as your children's needs change.

By implementing these strategies, you can create a more stable and predictable financial environment for your children while reducing potential conflicts with your co-parent.",
            'published' => true,
            'published_at' => now()->subDays(5),
            'user_id' => $adminUser->id,
        ]);
    }
}
