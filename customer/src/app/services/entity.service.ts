import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

@Injectable({
  providedIn: "root"
})
export class EntityService {
  private baseURL: string = environment.baseURL + `/api`;

  constructor(private http: HttpClient) {}

  entity(slug) {
    return this.http.get(this.baseURL + "/entity/entity/" + slug);
  }

  menus() {
    return this.http.get(this.baseURL + "/menu/menus");
  }
}
