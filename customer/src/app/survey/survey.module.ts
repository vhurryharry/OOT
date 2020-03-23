import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { CommonModule } from "@angular/common";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { SurveyRoutingModule } from "./survey-routing.module";
import { SurveyComponent } from "./survey.component";
import { SurveyService } from "./survey.service";

@NgModule({
  imports: [SurveyRoutingModule, CommonModule, SharedModule, FormsModule],
  declarations: [SurveyComponent],
  providers: [SurveyService]
})
export class SurveyModule {}
