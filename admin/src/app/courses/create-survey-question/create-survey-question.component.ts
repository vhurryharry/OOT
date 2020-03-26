import {
  Component,
  Input,
  OnChanges,
  OnInit,
  EventEmitter,
  Output
} from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";

@Component({
  selector: "admin-create-survey-question",
  templateUrl: "./create-survey-question.component.html"
})
export class CreateSurveyQuestionComponent implements OnChanges, OnInit {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  @Input()
  courseId: string;

  loading = false;
  questionForm = this.fb.group({
    id: [""],
    question: ["", Validators.required],
    type: ["", Validators.required]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnInit() {}

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find("survey/questions", this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.questionForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.questionForm.value.id;
      this.repository
        .create("survey/questions", {
          ...this.questionForm.value,
          courseId: this.courseId
        })
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.questionForm.value);
        });
    } else {
      this.repository
        .update("survey/questions", {
          ...this.questionForm.value,
          courseId: this.courseId
        })
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.questionForm.value);
        });
    }
  }
}
