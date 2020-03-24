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
  results = [];

  numbers = Array(10)
    .fill(0)
    .map((x, i) => i + 1);

  interests = [
    {
      key: "A",
      text: "Very interested"
    },
    {
      key: "B",
      text: "Maybe interested"
    },
    {
      key: "C",
      text: "Not interested"
    }
  ];

  constructor(
    private loginService: LoginService,
    private surveyService: SurveyService,
    private route: ActivatedRoute
  ) {
    this.route.params.subscribe(params => {
      this.slug = params.slug;

      this.surveyService.getQuestions(this.slug).subscribe((result: any) => {
        this.questions = result.questions;
        this.results = result.questions.map(question => {
          switch (question.type) {
            case "rating":
            case "interest":
              return 0;

            case "comment":
              return {
                comment: "",
                rows: 1
              };

            default:
              return "";
          }
        });
      });
    });
  }

  ngOnInit() {}

  onStartSurvey() {
    if (this.questions.length > 0) {
      this.index = 1;
    }
  }

  onPrevious(check = false) {
    if (check) {
      if (this.questions[this.index - 1].type === "comment") {
        return;
      }
    }

    this.index--;
  }

  onNext(check = false, fromEnter = false) {
    if (check) {
      if (this.questions[this.index - 1].type === "comment") {
        if (fromEnter) {
          if (window.screen.width < 576) {
            return;
          }
        } else {
          return;
        }
      }
    }

    if (this.questions.length >= this.index) {
      this.index++;
    }
  }

  onRate(rating, index) {
    this.results[index] = rating;
    this.onNext();
  }

  onInterest(key, index) {
    this.results[index] = key;
    this.onNext();
  }

  onComment(event, index) {
    this.results[index].comment = event.target.value;
    const maxRows = 5;
    const rows = this.results[index].comment.split("\n").length;
    this.results[index].rows = rows > maxRows ? maxRows : rows;
  }

  onSubmitSurvey() {}
}
