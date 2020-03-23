import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { CondensedComponent } from "./layout/condensed/condensed.component";
import { AuthGuard } from "./services/auth-guard.service";

const routes: Routes = [
  {
    path: "",
    component: CondensedComponent,
    loadChildren: "./home/home.module#HomeModule"
  },
  {
    path: "contact",
    component: CondensedComponent,
    loadChildren: "./contact/contact.module#ContactModule"
  },
  {
    path: "about",
    component: CondensedComponent,
    loadChildren: "./about/about.module#AboutModule"
  },
  {
    path: "course",
    component: CondensedComponent,
    loadChildren: "./course/course.module#CourseModule"
  },
  {
    path: "account",
    component: CondensedComponent,
    loadChildren: "./account/account.module#AccountModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "survey",
    component: CondensedComponent,
    loadChildren: "./survey/survey.module#SurveyModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "faq",
    component: CondensedComponent,
    loadChildren: "./faq/faq.module#FAQModule"
  },
  {
    path: "terms",
    component: CondensedComponent,
    loadChildren: "./terms/terms.module#TermsModule"
  },
  {
    path: "blog",
    component: CondensedComponent,
    loadChildren: "./blog/blog.module#BlogModule"
  },
  {
    path: "cart",
    component: CondensedComponent,
    loadChildren: "./cart/cart.module#CartModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "payment",
    component: CondensedComponent,
    loadChildren: "./payment/payment.module#PaymentModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "invoice",
    component: CondensedComponent,
    loadChildren: "./invoice/invoice.module#InvoiceModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "login",
    component: CondensedComponent,
    loadChildren: "./auth/login/login.module#LoginModule"
  },
  {
    path: "signup",
    component: CondensedComponent,
    loadChildren: "./auth/signup/signup.module#SignupModule"
  },
  {
    path: "email-confirmation",
    component: CondensedComponent,
    loadChildren:
      "./auth/email-confirmation/email-confirmation.module#EmailConfirmationModule"
  },
  {
    path: "forgot-pwd",
    component: CondensedComponent,
    loadChildren: "./auth/forgot-pwd/forgot-pwd.module#ForgotPwdModule"
  },
  {
    path: "reset-pwd",
    component: CondensedComponent,
    loadChildren: "./auth/reset-pwd/reset-pwd.module#ResetPwdModule"
  },
  {
    path: "**",
    redirectTo: "",
    pathMatch: "full"
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
