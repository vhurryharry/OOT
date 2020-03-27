import { Component, OnInit } from "@angular/core";
import { FormBuilder } from "@angular/forms";
import { Location } from "@angular/common";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute, Router } from "@angular/router";
import { NavigationService } from "src/app/services/navigation.service";

@Component({
  selector: "admin-manage-survey-questions",
  templateUrl: "./manage-survey-questions.component.html"
})
export class ManageSurveyQuestionsComponent implements OnInit {
  loading = false;
  questions = [];
  courseId: string = null;
  pageTitle = "Manage Course Survey Questions";

  showCreateQuestion = false;
  showEditQuestion = false;
  singleSelection = null;

  constructor(
    private fb: FormBuilder,
    private navigationService: NavigationService,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private location: Location,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
    });
  }

  ngOnInit() {
    if (this.courseId && this.courseId !== "0") {
      this.load();
    }
  }

  load() {
    this.loading = true;
    this.repository
      .custom("survey/questions", { id: this.courseId }, "findByCourse")
      .subscribe((result: any) => {
        this.loading = false;
        this.questions = result.items;
      });
  }

  onEdit(question) {
    this.showEditQuestion = true;
    this.singleSelection = question;
  }

  onRemove(id) {
    this.loading = true;
    this.repository
      .delete("survey/questions", [id])
      .subscribe((result: any) => {
        this.ngOnInit();
      });
  }

  onRestore(id) {
    this.loading = true;
    this.repository
      .restore("survey/questions", [id])
      .subscribe((result: any) => {
        this.ngOnInit();
      });
  }

  goBack() {
    this.location.back();
  }

  onAdd() {
    this.showCreateQuestion = true;
  }

  onContentUpdated() {
    this.showCreateQuestion = false;
    this.showEditQuestion = false;
    this.load();
  }
}
