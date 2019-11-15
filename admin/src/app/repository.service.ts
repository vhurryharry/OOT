import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class RepositoryService {
  constructor(private http: HttpClient) { }

  fetch(module, state) {
    return this.http.post('/admin/api/' + module + '/search', {state});
  }

  delete(module, ids) {
    return this.http.post('/admin/api/' + module + '/delete', {ids});
  }

  restore(module, ids) {
    return this.http.post('/admin/api/' + module + '/restore', {ids});
  }
}
