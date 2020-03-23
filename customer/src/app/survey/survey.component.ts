import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";

import { LoginService } from "src/app/services/login.service";
import { SurveyService } from "./survey.service";

@Component({
  selector: "app-survey",
  templateUrl: "./survey.component.html",
  styleUrls: ["./survey.component.scss"]
})
export class SurveyComponent implements OnInit {
  index = 0;
  slug = "";

  questions = [];
  numbers = Array(10)
    .fill(0)
    .map((x, i) => i + 1);

  constructor(
    private loginService: LoginService,
    private surveyService: SurveyService,
    private route: ActivatedRoute
  ) {
    this.route.params.subscribe(params => {
      this.slug = params.slug;

      this.surveyService.getQuestions(this.slug).subscribe((result: any) => {
        this.questions = result.questions;
      });
    });
  }

  ngOnInit() {}

  onStartSurvey() {
    this.index = 1;
  }
}
