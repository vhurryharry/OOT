import { Component, OnChanges, OnInit } from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute, Router } from "@angular/router";

@Component({
  selector: "admin-create-student",
  templateUrl: "./create-student.component.html"
})
export class CreateStudentComponent implements OnInit {
  pageTitle = "";
  studentId: string = null;
  loading = false;
  studentForm = this.fb.group({
    id: [""],
    status: ["", Validators.required],
    confirmationToken: [""],
    acceptsMarketing: [""],
    expiresAt: [""],
    login: ["", Validators.required],
    passwordExpiresAt: [""],
    firstName: [""],
    lastName: [""],
    tagline: [""],
    occupation: [""],
    birthDate: [""],
    password: [""],
    mfa: [""]
  });

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.studentId = params.id;
      if (params.id === "0") {
        this.studentId = null;
      }

      if (this.studentId) {
        this.pageTitle = "Edit Student";
      } else {
        this.pageTitle = "Add New Student";
      }
    });
  }

  ngOnInit() {
    if (this.studentId) {
      this.loading = true;
      this.repository
        .find("customer", this.studentId)
        .subscribe((result: any) => {
          this.loading = false;
          this.studentForm.patchValue(result);
        });
    }
  }

  goBack() {
    this.router.navigate(["/students"]);
  }

  onSubmit() {
    this.loading = true;
    const payload = this.studentForm.value;
    payload.type = "student";

    if (!this.studentId) {
      delete payload.id;
      this.repository.create("customer", payload).subscribe((result: any) => {
        this.loading = false;
        this.router.navigate(["/students"]);
      });
    } else {
      this.repository.update("customer", payload).subscribe((result: any) => {
        this.loading = false;
        this.router.navigate(["/students"]);
      });
    }
  }
}
