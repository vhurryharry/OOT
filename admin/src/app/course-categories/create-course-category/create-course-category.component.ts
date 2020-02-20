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
  selector: "admin-create-course-category",
  templateUrl: "./create-course-category.component.html"
})
export class CreateCourseCategoryComponent implements OnChanges, OnInit {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  categories = [];
  categoryForm = this.fb.group({
    id: [""],
    category: ["", Validators.required]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnInit() {
    this.loading = true;
    this.repository.fetch("course_category", {}).subscribe((result: any) => {
      this.categories = result.items;
      this.loading = false;
    });
  }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find("course_category", this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.categoryForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.categoryForm.value.id;
      this.repository
        .create("course_category", this.categoryForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.categoryForm.value);
        });
    } else {
      this.repository
        .update("course_category", this.categoryForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.categoryForm.value);
        });
    }
  }
}
