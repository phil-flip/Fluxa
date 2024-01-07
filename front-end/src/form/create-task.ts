import {z} from "zod";

export const schema = z.object({
    projectId: z.string(),
    teamId: z.string(),
    title: z.string(),
    description: z.string(),
    statusId: z.string(),
    milestoneId: z.string(),
    cycleId: z.string(),
    labelIds: z.string().array(),
    groupIds: z.string().array(),
    componentIds: z.string().array(),
});
