import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { DashboardComponent } from './dashboard/dashboard.component';

// Entities
import { EntityListComponent } from './entities/entity-list/entity-list.component';
import { CreateEntityComponent } from './entities/create-entity/create-entity.component';

// Notifications
import { NotificationListComponent } from './notifications/notification-list/notification-list.component';
import { CreateNotificationComponent } from './notifications/create-notification/create-notification.component';

// Categories
import { CategoryListComponent } from './categories/category-list/category-list.component';
import { CreateCategoryComponent } from './categories/create-category/create-category.component';

// Tags
import { TagListComponent } from './tags/tag-list/tag-list.component';
import { CreateTagComponent } from './tags/create-tag/create-tag.component';

// Menu
import { MenuListComponent } from './menus/menu-list/menu-list.component';
import { CreateMenuComponent } from './menus/create-menu/create-menu.component';

// FAQ
import { FaqListComponent } from './faqs/faq-list/faq-list.component';
import { CreateFaqComponent } from './faqs/create-faq/create-faq.component';

// Documents
import { DocumentListComponent } from './documents/document-list/document-list.component';
import { CreateDocumentComponent } from './documents/create-document/create-document.component';

// Users
import { UserListComponent } from './users/user-list/user-list.component';
import { CreateUserComponent } from './users/create-user/create-user.component';

// Roles
import { RoleListComponent } from './roles/role-list/role-list.component';
import { CreateRoleComponent } from './roles/create-role/create-role.component';

// Audit
import { AuditLogListComponent } from './audit-logs/audit-log-list/audit-log-list.component';

// Students
import { StudentListComponent } from './students/student-list/student-list.component';
import { CreateStudentComponent } from './students/create-student/create-student.component';

// Instructors
import { InstructorListComponent } from './instructors/instructor-list/instructor-list.component';
import { CreateInstructorComponent } from './instructors/create-instructor/create-instructor.component';

// Orders
import { OrderListComponent } from './orders/order-list/order-list.component';
import { OrderPaymentsComponent } from './orders/order-payments/order-payments.component';
import { EditOrderComponent } from './orders/edit-order/edit-order.component';

@NgModule({
  declarations: [
    AppComponent,
    DashboardComponent,
    EntityListComponent,
    CreateEntityComponent,
    NotificationListComponent,
    CreateNotificationComponent,
    CategoryListComponent,
    CreateCategoryComponent,
    TagListComponent,
    CreateTagComponent,
    MenuListComponent,
    CreateMenuComponent,
    FaqListComponent,
    CreateFaqComponent,
    DocumentListComponent,
    CreateDocumentComponent,
    UserListComponent,
    CreateUserComponent,
    RoleListComponent,
    CreateRoleComponent,
    AuditLogListComponent,
    StudentListComponent,
    CreateStudentComponent,
    InstructorListComponent,
    CreateInstructorComponent,
    OrderListComponent,
    OrderPaymentsComponent,
    EditOrderComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ClarityModule,
    BrowserAnimationsModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    CKEditorModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {}
