import { Component, OnInit } from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { Location } from "@angular/common";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute } from "@angular/router";

@Component({
  selector: "admin-manage-topics",
  templateUrl: "./manage-topics.component.html"
})
export class ManageTopicsComponent implements OnInit {
  loading = false;
  topics = [];
  availableTopics = [];
  topicForm = this.fb.group({
    topic: ["", Validators.required]
  });
  courseId: string = null;
  pageTitle = "Manage Course Topics";

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
    this.loading = true;
    this.repository.fetch("course_topic", {}).subscribe((result: any) => {
      this.availableTopics = result.items;
      this.loading = false;
    });

    this.loadTopics();
  }

  loadTopics() {
    if (this.courseId && this.courseId !== "0") {
      this.loading = true;
      this.repository
        .find("course/topics", this.courseId)
        .subscribe((result: any) => {
          this.loading = false;
          this.topics = result;
        });
    }
  }

  onSubmit() {
    this.loading = true;

    this.repository
      .create("course/topics", {
        course_id: this.courseId,
        topic_id: this.topicForm.value.topic
      })
      .subscribe((result: any) => {
        this.loading = false;
        this.loadTopics();
      });
  }

  onRemove(id) {
    this.loading = true;

    this.repository
      .delete("course/topics", {
        course_id: this.courseId,
        topic_id: id
      })
      .subscribe((result: any) => {
        this.loading = false;
        this.loadTopics();
      });
  }

  isAvailable(selectedInstructor) {
    for (const instructor of this.topics) {
      if (instructor.id === selectedInstructor.id) {
        return false;
      }
    }

    return true;
  }

  goBack() {
    this.location.back();
  }
}
