import { Component, OnInit } from "@angular/core";
import { FormArray, FormBuilder, Validators } from "@angular/forms";
import { Location } from "@angular/common";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute } from "@angular/router";

@Component({
  selector: "admin-manage-options",
  templateUrl: "./manage-options.component.html",
  styleUrls: ["./manage-options.component.scss"]
})
export class ManageOptionsComponent implements OnInit {
  loading = false;
  showForm = false;
  courseId: string = null;
  options = [];
  pageTitle = "Manage Course Options";
  isAddForm = false;

  optionForm = this.fb.group({
    id: [""],
    title: ["", Validators.required],
    price: ["", Validators.required],
    combo: [false],
    dates: this.fb.array([this.fb.control("")])
  });

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private location: Location
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
    });
  }

  ngOnInit() {
    if (this.courseId && this.courseId !== "0") {
      this.loading = true;
      this.repository
        .find("course/options", this.courseId)
        .subscribe((result: any) => {
          this.loading = false;
          this.options = result;
        });
    }
  }

  onAddOption() {
    if (this.showForm && this.isAddForm) {
      this.showForm = false;
      this.optionForm.reset();
    } else {
      this.showForm = true;
      this.isAddForm = true;
      this.optionForm.reset();
      this.setDates([""]);
    }
  }

  onEditOption(option) {
    if (option.deletedAt === null) {
      if (this.showForm && this.optionForm.get("id").value === option.id) {
        this.showForm = false;
        this.optionForm.reset();
      } else {
        this.showForm = true;
        this.isAddForm = false;
        this.optionForm.patchValue(option);
        this.setDates(option.dates);
      }
    }
  }

  onSubmit() {
    this.loading = true;
    const payload = this.optionForm.value;
    payload.course = this.courseId;

    if (this.isAddForm) {
      delete payload.id;
      this.repository
        .create("course/options", payload)
        .subscribe((result: any) => {
          this.loading = false;
          this.ngOnInit();
        });
    } else {
      this.repository
        .update("course/options", payload)
        .subscribe((result: any) => {
          this.loading = false;
          this.ngOnInit();
        });
    }
  }

  onAction(option) {
    this.loading = true;
    if (option.deletedAt) {
      this.repository
        .restore("course/options", [option.id])
        .subscribe((result: any) => {
          this.ngOnInit();
        });
    } else {
      this.repository
        .delete("course/options", [option.id])
        .subscribe((result: any) => {
          this.ngOnInit();
        });
    }
  }

  get dates() {
    return this.optionForm.get("dates") as FormArray;
  }

  setDates(dates) {
    this.dates.clear();
    dates.forEach(date => {
      this.dates.push(this.fb.control(date));
    });
  }

  addDate() {
    this.dates.push(this.fb.control(""));
  }

  removeDate(index) {
    this.dates.removeAt(index);
  }

  goBack() {
    this.location.back();
  }
}
