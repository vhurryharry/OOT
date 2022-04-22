import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { CondensedComponent } from "./layout/condensed/condensed.component";
import { AuthGuard } from "./services/auth-guard.service";

import { PageComponent } from "./layout/page/page.component";

const routes: Routes = [
  {
    path: "",
    component: CondensedComponent,
    loadChildren: () => import("./home/home.module").then((x) => x.HomeModule),
  },
  {
    path: "contact",
    component: CondensedComponent,
    loadChildren: () =>
      import("./contact/contact.module").then((x) => x.ContactModule),
  },
  {
    path: "about",
    component: CondensedComponent,
    loadChildren: () =>
      import("./about/about.module").then((x) => x.AboutModule),
  },
  {
    path: "course",
    component: CondensedComponent,
    loadChildren: () =>
      import("./course/course.module").then((x) => x.CourseModule),
  },
  {
    path: "account",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
    loadChildren: () =>
      import("./account/account.module").then((x) => x.AccountModule),
  },
  {
    path: "survey",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
    loadChildren: () =>
      import("./survey/survey.module").then((x) => x.SurveyModule),
  },
  {
    path: "faq",
    component: CondensedComponent,
    loadChildren: () => import("./faq/faq.module").then((x) => x.FAQModule),
  },
  {
    path: "blog",
    component: CondensedComponent,
    loadChildren: () => import("./blog/blog.module").then((x) => x.BlogModule),
  },
  {
    path: "cart",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
    loadChildren: () => import("./cart/cart.module").then((x) => x.CartModule),
  },
  {
    path: "payment",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
    loadChildren: () =>
      import("./payment/payment.module").then((x) => x.PaymentModule),
  },
  {
    path: "invoice",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
    loadChildren: () =>
      import("./invoice/invoice.module").then((x) => x.InvoiceModule),
  },
  {
    path: "login",
    component: CondensedComponent,
    loadChildren: () =>
      import("./auth/login/login.module").then((x) => x.LoginModule),
  },
  {
    path: "signup",
    component: CondensedComponent,
    loadChildren: () =>
      import("./auth/signup/signup.module").then((x) => x.SignupModule),
  },
  {
    path: "email-confirmation",
    component: CondensedComponent,
    loadChildren: () =>
      import("./auth/email-confirmation/email-confirmation.module").then(
        (x) => x.EmailConfirmationModule
      ),
  },
  {
    path: "forgot-pwd",
    component: CondensedComponent,
    loadChildren: () =>
      import("./auth/forgot-pwd/forgot-pwd.module").then(
        (x) => x.ForgotPwdModule
      ),
  },
  {
    path: "reset-pwd",
    component: CondensedComponent,
    loadChildren: () =>
      import("./auth/reset-pwd/reset-pwd.module").then((x) => x.ResetPwdModule),
  },
  {
    path: ":slug",
    component: PageComponent,
  },
  {
    path: "**",
    redirectTo: "",
    pathMatch: "full",
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
