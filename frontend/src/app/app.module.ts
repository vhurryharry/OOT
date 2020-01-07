import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

// Layout
import { CondensedComponent } from './layout/condensed/condensed.component';
import { BlankComponent } from './layout/blank/blank.component';
import { HeaderComponent } from './layout/shared/header/header.component';
import { FooterComponent } from './layout/shared/footer/footer.component';

import { NavigationService } from './services/navigation.service';
import { LoginService } from './services/login.service';
import { AuthGuard } from './services/auth-guard.service';

@NgModule({
  declarations: [
    AppComponent,
    CondensedComponent,
    BlankComponent,
    HeaderComponent,
    FooterComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [AuthGuard, LoginService, NavigationService],
  bootstrap: [AppComponent]
})
export class AppModule {}
