import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { NgModule } from "@angular/core";
import { HttpClientModule } from "@angular/common/http";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { DashboardComponent } from "./dashboard/dashboard.component";

// Layout
import { CondensedComponent } from "./layout/condensed/condensed.component";
import { BlankComponent } from "./layout/blank/blank.component";
import { HeaderComponent } from "./layout/shared/header/header.component";

import { NavigationService } from "./services/navigation.service";
import { LoginService } from "./services/login.service";
import { AuthGuard } from "./services/auth-guard.service";

@NgModule({
  declarations: [
    AppComponent,
    DashboardComponent,
    CondensedComponent,
    BlankComponent,
    HeaderComponent
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
  providers: [AuthGuard, LoginService, NavigationService],
  bootstrap: [AppComponent]
})
export class AppModule {}
