import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";

import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";
import { CondensedComponent } from "./layout/condensed/condensed.component";
import { FooterComponent } from "./layout/shared/footer/footer.component";
import { BlankComponent } from "./layout/blank/blank.component";

@NgModule({
  declarations: [
    AppComponent,
    CondensedComponent,
    FooterComponent,
    BlankComponent
  ],
  imports: [BrowserModule, AppRoutingModule],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {}
