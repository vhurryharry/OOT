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
  selector: "admin-create-course-topic",
  templateUrl: "./create-course-topic.component.html"
})
export class CreateCourseTopicComponent implements OnChanges, OnInit {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  topics = [];
  topicForm = this.fb.group({
    id: [""],
    topic: ["", Validators.required],
    description: ["", Validators.required]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnInit() {
    this.loading = true;
    this.repository.fetch("course_topic", {}).subscribe((result: any) => {
      this.topics = result.items;
      this.loading = false;
    });
  }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find("course_topic", this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.topicForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.topicForm.value.id;
      this.repository
        .create("course_topic", this.topicForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.topicForm.value);
        });
    } else {
      this.repository
        .update("course_topic", this.topicForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.topicForm.value);
        });
    }
  }
}
