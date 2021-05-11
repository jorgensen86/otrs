<template>
  <b-container fluid>
    <b-row>
      <b-col md="9">
        <div class="timeline">
          <!-- Timeline time label -->
          <div class="time-label">
            <span class="bg-green">{{ ticket.created_at }}</span>
          </div>
          <div>
            <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-envelope bg-blue"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
              <!-- Time -->
              <span class="time"><i class="fas fa-clock"></i> 12:05</span>
              <!-- Header. Optional -->
              <h3 class="timeline-header">
                <a href="#">{{ ticket.user.name }}</a> άνοιξε ένα ticket
              </h3>
              <!-- Body -->
              <div class="timeline-body">
                {{ ticket.message }}
              </div>
              <!-- Placement of additional controls. Optional -->
              <div class="timeline-footer">
                <a class="btn btn-primary btn-sm">Read more</a>
                <a class="btn btn-danger btn-sm">Delete</a>
              </div>
            </div>
          </div>
          <!-- The last icon means the story is complete -->
          <div>
            <i class="fas fa-clock bg-gray"></i>
          </div>
        </div>
        <div class="timeline" v-for="reply in replies" :key="reply.id">
          <!-- Timeline time label -->
          <div class="time-label">
            <span class="bg-green">{{ reply.created_at}}</span>
          </div>
          <div>
            <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-envelope bg-blue"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
              <!-- Time -->
              <span class="time"><i class="fas fa-clock"></i> 12:05</span>
              <!-- Header. Optional -->
              <h3 class="timeline-header">
                <a href="#">{{ reply.user.name }}</a> απάντησε
              </h3>
              <!-- Body -->
              <div class="timeline-body">
                {{ reply.message }}
              </div>
              <!-- Placement of additional controls. Optional -->
              <div class="timeline-footer">
                <a class="btn btn-primary btn-sm">Read more</a>
                <a class="btn btn-danger btn-sm">Delete</a>
              </div>
            </div>
          </div>
          <!-- The last icon means the story is complete -->
          <div>
            <i class="fas fa-clock bg-gray"></i>
          </div>
        </div>
      </b-col>
      <b-col md="3">
        <b-card title="Card Title" class="mb-2">
          <b-card-text>
            Some quick example text to build on the card title and make up the
            bulk of the card's content.
          </b-card-text>

          <b-button href="#" variant="primary">Go somewhere</b-button>
        </b-card>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
export default {
  data() {
    return {
      data: {
        id: null,
        name: "",
        status: "",
      },
      ticket: [],
      replies: []
    };
  },
  async created() {
    const res = await this.callApi(
      "get",
      "/editTicket/" + this.$route.params.id
    );
    this.ticket = res.data;
    const result = await this.callApi(
      "get",
      "/getReplies"
    );
    this.replies = result.data;
  },
  methods: {
    async saveStatus() {
      if (this.modalData.name.trim() == "") return alert();

      const res = await this.callApi(
        "post",
        "/saveTicketStatus",
        this.modalData
      );
      if (res.status == 200 || res.status == 201) {
        this.tickets[this.index] = res.data;
        this.$refs.table.refresh();
        this.$refs.someModal.hide();
        // this.tags.unshift(res.data);
        this.makeToaster();

        // this.s("Tag added successfully!!");
        // this.addModal = false;
      } else {
        // this.swr("");
      }
    },
  },
};
</script>