import {
  Component,
  Input,
  OnChanges,
  OnInit,
  EventEmitter,
  Output
} from "@angular/core";
import {
  FormArray,
  FormBuilder,
  FormGroup,
  FormControl,
  Validators
} from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";

@Component({
  selector: "admin-create-faq",
  templateUrl: "./create-faq.component.html"
})
export class CreateFaqComponent implements OnChanges, OnInit {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  courses = [];
  faqForm = this.fb.group({
    id: [""],
    title: ["", Validators.required],
    content: ["", Validators.required],
    course: [""]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnInit() {
    this.loading = true;
    this.repository.fetch("course", {}).subscribe((result: any) => {
      this.courses = result.items;
      this.loading = false;
    });
  }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository.find("faq", this.update.id).subscribe((result: any) => {
        this.loading = false;
        this.faqForm.patchValue(result);
      });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.faqForm.value.id;
      this.repository
        .create("faq", this.faqForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.faqForm.value);
        });
    } else {
      this.repository
        .update("faq", this.faqForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.faqForm.value);
        });
    }
  }
}
